<?php

declare(strict_types=1);

namespace Mcfedr\JsonFormBundle\Exception;

use RecursiveArrayIterator;
use RecursiveIteratorIterator;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormInterface;

class InvalidFormHttpException extends JsonHttpException
{
    public function __construct(FormInterface $form)
    {
        $data = $this->getAllErrors($form);
        parent::__construct(400, implode(' ', $this->simpleError($data)), $data);
    }

    public function simpleError(array $errors): array
    {
        $iterator = new RecursiveArrayIterator($errors);
        $recursive = new RecursiveIteratorIterator(
            $iterator,
            RecursiveIteratorIterator::SELF_FIRST
        );
        $err = [];
        foreach ($recursive as $key => $value) {
            if ('errors' === $key && \count($value) > 0) {
                foreach ($value as $error) {
                    $err[] = $error['message'];
                }
            }
        }

        return $err;
    }

    /**
     * @return ?\Symfony\Component\Form\FormError[]
     */
    protected function getAllErrors(FormInterface $form): ?array
    {
        $errors = $form->getErrors();
        $children = $form->all();
        if (!\count($errors) && !\count($children)) {
            return null;
        }

        $section = [
            'field' => $form->getName(),
        ];

        if (\count($errors)) {
            $section['errors'] = array_map(
                function (FormError $error) {
                    return [
                        'message' => $error->getMessage(),
                        'parameters' => $error->getMessageParameters(),
                    ];
                },
                iterator_to_array($form->getErrors())
            );
        }

        if (\count($children)) {
            $section['children'] = array_values(
                array_filter(
                    array_map(
                        function (FormInterface $field) {
                            return $this->getAllErrors($field);
                        },
                        $form->all()
                    )
                )
            );

            if (!\count($errors) && !\count($section['children'])) {
                return null;
            }
        }

        return $section;
    }
}
