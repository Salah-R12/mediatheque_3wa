<?php

namespace App\Form;

use App\Entity\MediaType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;


class MediaTypeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $nameOptions = [
            'label' => 'Type de média'
        ];
        $borrowDurationOptions = [
            'label' => 'Durée max. de l\'emprunt (en jours)'
        ];
        $builder
            ->add('name', TextType::class, $nameOptions)
            ->add('borrow_duration', NumberType::class, $borrowDurationOptions);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => MediaType::class,
        ]);
    }
}
