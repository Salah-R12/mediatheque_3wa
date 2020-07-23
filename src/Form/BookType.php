<?php

namespace App\Form;

use App\Entity\Book;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BookType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, ['label' => 'Titre'])
            ->add('author', null, ['label' => 'Auteur'])
            ->add('description', null, ['label' => 'Description'])
            ->add('edition', null, ['label' => 'Édition'])
            ->add('page_nb', null, ['label' => 'Nombre de pages'])
            //->add('media_type')
        	->add('digitalMedia', DigitalMediaType::class, ['label' => 'Support digital (optionnel) :'])
        	->add('stockableMedia', StockableMediaType::class, ['label' => 'Logistique (optionnel) :'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Book::class,
        ]);
    }
}
