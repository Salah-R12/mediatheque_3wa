<?php
namespace App\Form;
use App\Entity\Borrow;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Repository\StockableMediaCopyRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\StockableMediaCopy;

class BorrowType extends AbstractType{

	/**
	 * @var \App\Repository\StockableMediaCopyRepository
	 */
	protected $stockableMediaCopyRepo;

	/**
	 * Inject instance of StockableMediaCopyRepository
	 *
	 * @param StockableMediaCopyRepository $stockableMediaCopyRepo
	 */
	public function __construct(StockableMediaCopyRepository $stockableMediaCopyRepo){
		$this->stockableMediaCopyRepo = $stockableMediaCopyRepo;
	}

	public function buildForm(FormBuilderInterface $builder, array $options){
		// Since this form type is used in "edit" borrow form, then, will take loaded data from borrow object
		/**
		 * @var \App\Entity\Borrow
		 */
		$borrow = is_a($options['data'], Borrow::class) ? $options['data'] : new Borrow();
		// Passing "stockableMediaCopyRepo" property into variable to be able to "use" it in the "closure" function
		$stockableMediaCopyRepo = $this->stockableMediaCopyRepo;
		$queryBuilder = function () use ($stockableMediaCopyRepo, $borrow){
			return $stockableMediaCopyRepo->findAllAvailable(\Doctrine\ORM\QueryBuilder::class, $borrow->getStockableMediaCopy()->getId());
		};
		
		$builder->add('borrow_date', DateType::class, [
				'widget' => 'single_text',
				'html5' => true
			])
			->add('expiry_date', DateType::class, [
				'widget' => 'single_text',
				'html5' => true,
				'attr' => [
					'readonly' => true // This field is not manually updatable, but calculated
				]
			])
			->add('member')
			->add('stockable_media_copy', EntityType::class, [
				'class' => StockableMediaCopy::class,
				// Closure function using property "stockableMediaCopyRepo"
				'query_builder' => $queryBuilder
			])
			->add('return_date', DateType::class, [
				'widget' => 'single_text',
				'html5' => true
			])
			->add('return_media_state');
	}

	public function configureOptions(OptionsResolver $resolver){
		$resolver->setDefaults([
			'data_class' => Borrow::class
		]);
	}
}
