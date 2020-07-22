<?php

namespace App\Form;

use App\Entity\StockableMedia;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StockableMediaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('stock', null, ['label' => "Nombre d'exemplaires"])
            ->add('reception_date', DateType::class, [
            	'label' => 'Date de réception',
            	'widget' => 'single_text',
            	'html5' => true,
            	'required' => false
            ])
            //->add('media')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => StockableMedia::class,
        ]);
    }
}
