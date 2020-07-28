<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ResearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('research', TextType::class, [
                'attr' => [
                    'class' => 'form-control mr-sm-2'
                ]
            ])
            ->add('Search', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-outline-success my-2 my-sm-0'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
