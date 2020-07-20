<?php

namespace App\Form;

use App\Entity\Borrow;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class BorrowType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('borrow_date', DateTimeType::class, [
            	'widget' => 'single_text',
            	'attr' => [
            		'placeholder' => date('d/m/Y H:i:s')
            	]
            ])
            //->add('expiry_date')
            ->add('return_date')
            ->add('member')
            ->add('stockable_media_copy')
            //->add('return_media_state')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Borrow::class,
        ]);
    }
}
