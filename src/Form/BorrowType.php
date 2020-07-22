<?php

namespace App\Form;

use App\Entity\Borrow;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Repository\StockableMediaCopyRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\StockableMedia;

class BorrowType extends AbstractType
{
	
	private $stockableMediaCopyRepo;
	
	/**
	 * Inject instance of StockableMediaCopyRepository
	 * @param StockableMediaCopyRepository $stockableMediaCopyRepo
	 */
	public function __construct(StockableMediaCopyRepository $stockableMediaCopyRepo){
		$this->stockableMediaCopyRepo = $stockableMediaCopyRepo;
	}
	
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
    	$smc = $this->stockableMediaCopyRepo;
        $builder
            ->add('borrow_date')
            ->add('expiry_date')
            ->add('return_date')
            ->add('member')
            ->add('stockable_media_copy', EntityType::class, [
                'class' => StockableMedia::class,
                'query_builder' => function() use ($smc){
                	// TODO: use findAllAvailable()
                    return $smc->findAll();
                }
            ])
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
