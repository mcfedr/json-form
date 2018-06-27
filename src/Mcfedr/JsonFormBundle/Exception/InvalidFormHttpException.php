<?php

namespace Mcfedr\JsonFormBundle\Exception;

use RecursiveArrayIterator;
use RecursiveIteratorIterator;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormInterface;

class InvalidFormHttpException extends JsonHttpException
{
    public function __construct(Form $form)
    {
        $data = $this->getAllErrors($form);
        parent::__construct(400, implode(' ', $this->simpleError($data)), $data);
    }

    /**
     * @param FormInterface $form
     *
     * @return \Symfony\Component\Form\FormError[]
     */
    protected function getAllErrors(FormInterface $form)
    {
        $errors = $form->getErrors();
        $children = $form->all();
        if (!count($errors) && !count($children)) {
            return null;
        }

        $section = [
            'field' => $form->getName()
        ];

        if (count($errors)) {
            $section['errors'] = array_map(
                function (FormError $error) {
                    return [
                        'message' => $error->getMessage(),
                        'parameters' => $error->getMessageParameters()
                    ];
                },
                iterator_to_array($form->getErrors())
            );
        }

        if (count($children)) {
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

            if (!count($errors) && !count($section['children'])) {
                return null;
            }
        }

        return $section;
    }

    public function simpleError(array $errors)
    {
        $iterator = new RecursiveArrayIterator($errors);
        $recursive = new RecursiveIteratorIterator(
            $iterator,
            RecursiveIteratorIterator::SELF_FIRST
        );
        $err = [];
        foreach ($recursive as $key => $value) {
            if ('errors' === $key && count($value) > 0) {
                foreach ($value as $error) {
                    $err[] = $error['message'];
                }
            }
        }

        return $err;
    }
}
