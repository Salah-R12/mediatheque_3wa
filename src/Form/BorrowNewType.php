<?php

namespace App\Form;

use App\Entity\Borrow;
use App\Entity\StockableMediaCopy;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BorrowNewType extends BorrowType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
    	// Passing "stockableMediaCopyRepo" property into variable to be able to "use" it in the "closure" function
    	$stockableMediaCopyRepo = $this->stockableMediaCopyRepo;
    	/**
    	 * Closure function returns a QueryBuilder object
    	 * @var \Doctrine\ORM\QueryBuilder $queryBuilder
    	 */
    	$queryBuilder = function () use ($stockableMediaCopyRepo){
    		return $stockableMediaCopyRepo->findAllAvailable(\Doctrine\ORM\QueryBuilder::class);
    	};
    	
    	$builder->add('borrow_date', DateType::class, [
	    		'widget' => 'single_text',
    			'html5' => true,
    			'label' => "Date de l'emprunt"
    		])
    		->add('member', null, [
    			'label' => 'Adhérent'
    		])
            ->add('stockable_media_copy', EntityType::class, [
            	'class' => StockableMediaCopy::class,
            	'label' => 'Exemplaire emprunté',
            	// Closure function using property "stockableMediaCopyRepo"
            	'query_builder' => $queryBuilder
            ])
        ;
    }
}
