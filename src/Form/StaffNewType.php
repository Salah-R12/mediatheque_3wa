<?php

namespace App\Form;

use App\Entity\Role;
use App\Entity\Staff;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StaffNewType extends StaffType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
    	$builder
    	->add('first_name', null, [
    		'label' => 'Prénom*'
    	])
    	->add('last_name', null, [
    		'label' => 'Nom*'
    	])
    	->add('username', null, [
    		'label' => 'Identifiant*'
    	])
    	->add('password', PasswordType::class, [
    		'label' => 'Mot de passe*'
    	])
    	->add('email', EmailType::class, [
    		'label' => 'E-mail*'
    	])
    	->add('address1', null, [
    		'label' => 'Adresse'
    	])
    	->add('address2', null, [
    		'label' => 'Complément d\'adresse'
    	])
    	->add('zipcode', null, [
    		'label' => 'Code postal'
    	])
    	->add('city', null, [
    		'label' => 'Ville'
    	])
    	->add('phone', null, [
    		'label' => 'Téléphone'
    	])
    	->add('roles', EntityType::class, [
    		'label' => 'Rôles',
    		'class' => Role::class,
    		'expanded' => true, // Combine expanded & multiple to display checkboxes
    		'multiple' => true
    	])
    	;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Staff::class,
        ]);
    }
}
