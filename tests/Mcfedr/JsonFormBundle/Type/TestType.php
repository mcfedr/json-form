<?php

declare(strict_types=1);

namespace Mcfedr\JsonFormBundle\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Email;

class TestType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('one', ChoiceType::class, [
                'choices' => ['value' => 'value'],
            ])
            ->add('two', CheckboxType::class)
            ->add('number', NumberType::class)
            ->add('email', EmailType::class, [
                'constraints' => new Email(),
            ])
        ;
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getBlockPrefix(): string
    {
        return 'form';
    }
}
