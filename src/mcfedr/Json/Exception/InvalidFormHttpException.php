<?php

namespace mcfedr\Json\Exception;

use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;

class InvalidFormHttpException extends HttpException
{
    public function __construct(Form $form)
    {
        parent::__construct(
            400,
            json_encode(
                [
                    'error' => 'Invalid data',
                    'errorInfo' => $this->getAllErrors($form)
                ]
            ),
            null,
            [
                'Content-Type' => 'application/json'
            ]
        );
    }

    /**
     * @param FormInterface $form
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
}
