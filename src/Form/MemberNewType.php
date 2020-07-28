<?php

namespace App\Form;

use App\Entity\Member;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;

class MemberNewType extends MemberType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('first_name', null, ['label' => 'Prénom*'])
            ->add('last_name', null, ['label' => 'Nom*'])
            ->add('username', null, ['label' => 'Identifiant*'])
            ->add('password', PasswordType::class, ['label' => 'Mot de passe*', 'attr' => ['class' => 'password']])
            ->add('email', EmailType::class, ['label' => 'E-mail*'])
            ->add('address1', null, ['label' => 'Adresse (rue)*'])
            ->add('address2', null, ['label' => 'Complément d\'adresse'])
            ->add('zipcode', null, ['label' => 'Code postal*'])
            ->add('city', null, ['label' => 'Ville*'])
            ->add('phone', null, ['label' => 'Téléphone (mobile ou domicile)'])
            ->add('created_by_staff', null, ['label' => 'Fiche créée par']) // TODO: mettre par défaut l'ID user du Staff connecté à l'interface (variable de Session)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Member::class,
        ]);
    }
}
