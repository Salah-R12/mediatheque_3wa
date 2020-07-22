<?php

namespace App\Form;

use App\Entity\Borrow;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class BorrowType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('borrow_date', DateType::class, [
                'widget' => 'single_text',
                'html5' => true,
                /*'attr' => ['class' => 'js-datepicker'],*/
                ])
            ->add('expiry_date',DateType::class, [
                'widget' => 'single_text',
                'html5' => true,
                ])
            ->add('return_date')
            ->add('member')
            ->add('stockable_media_copy')
            ->add('return_media_state')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Borrow::class,
        ]);
    }
}
