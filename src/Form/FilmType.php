<?php

namespace App\Form;

use App\Entity\Film;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use App\Form\DigitalMediaType;
use App\Form\StockableMediaType;

class FilmType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('author')
            ->add('description')
            ->add('original_title')
            ->add('synopsis')
            ->add('duration')
            ->add('director')
            ->add('actors')
            ->add('DigitalMedia', DigitalMediaType::class)
            ->add('StockableMedia', StockableMediaType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Film::class,
        ]);
    }
}
