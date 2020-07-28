<?php

namespace App\Form;

use App\Entity\Member;
use App\Entity\Staff;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Repository\StaffRepository;

class MemberType extends AbstractType
{
	
	/**
	 * @var \App\Repository\StaffRepository
	 */
	protected $staffRepo;
	
	/**
	 * Inject instance of StaffRepository
	 *
	 * @param StaffRepository $staffRepo
	 */
	public function __construct(StaffRepository $staffRepo){
		$this->staffRepo = $staffRepo;
	}
	
	public function buildForm(FormBuilderInterface $builder, array $options)
    {
		$choices = [];
		if (isset($options['data']) && method_exists($options['data'], 'getCreatedByStaff')){
			// On edit mode, restrict choices of staff (only display the one that has created the member)
			$staff = is_a($options['data']->getCreatedByStaff(), Staff::class) ? $options['data']->getCreatedByStaff() : new Staff();
			$choices = $this->staffRepo->findBy(['id' => $staff->getId()]);
		}else{
			$choices = $this->staffRepo->findAll();
		}
        $builder
            ->add('first_name', null, ['label' => 'Prénom*'])
            ->add('last_name', null, ['label' => 'Nom*'])
            ->add('username', null, ['label' => 'Identifiant*'])
            ->add('email', EmailType::class, ['label' => 'E-mail*'])
            ->add('address1', null, ['label' => 'Adresse (rue)*'])
            ->add('address2', null, ['label' => 'Complément d\'adresse'])
            ->add('zipcode', null, ['label' => 'Code postal*'])
            ->add('city', null, ['label' => 'Ville*'])
            ->add('phone', null, ['label' => 'Téléphone (mobile ou domicile)'])
            ->add('created_by_staff', EntityType::class, [
            	'class' => Staff::class,
            	'label' => 'Fiche créée par',
            	'attr' => ['readonly' => true],
            	'choices' => $choices
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Member::class,
        ]);
    }
}
