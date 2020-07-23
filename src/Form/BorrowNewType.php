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
    	
    	$builder->add('borrow_date', DateType::class, [
	    		'widget' => 'single_text',
	    		'html5' => true
    		])
            ->add('member')
            ->add('stockable_media_copy', EntityType::class, [
            	'class' => StockableMediaCopy::class,
            	// Closure function using property "stockableMediaCopyRepo"
            	'query_builder' => function () use ($stockableMediaCopyRepo){
            		return $stockableMediaCopyRepo->findAllAvailable(\Doctrine\ORM\QueryBuilder::class);
            	}
            ])
        ;
    }
}
