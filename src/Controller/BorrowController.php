<?php
namespace App\Controller;
use App\Entity\Borrow;
use App\Form\BorrowType;
use App\Form\BorrowNewType;
use App\Repository\BorrowRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\Entity\Borrow as BorrowService;

class BorrowController extends AbstractController{

	/**
	 *
	 * @Route("/list/borrow", name="borrow_index", methods={"GET"})
	 */
	public function index(BorrowRepository $borrowRepository): Response{
		return $this->render('borrow/index.html.twig', [
			'borrows' => $borrowRepository->findAll()
		]);
	}

	/**
	 *
	 * @Route("/admin/new/borrow", name="borrow_new", methods={"GET","POST"})
	 */
	public function new(Request $request): Response{
		$borrow = new Borrow();
		$form = $this->createForm(BorrowNewType::class, $borrow);
		$form->handleRequest($request);

		if ($form->isSubmitted()){
			if ($form->isValid()){
				try{
					$entityManager = $this->getDoctrine()->getManager();
					$entityManager->persist($borrow);
					$entityManager->flush();
		
					$this->addFlash('success', 'Données enregistrées avec succès');
					return $this->redirectToRoute('borrow_index');
				}catch(\Exception $ex){
					$this->addFlash('error', 'Une erreur est survenue lors de la création des données');
					throw $ex;
				}
			}else{
				$this->addFlash('warning', '');
			}
		}

		return $this->render('borrow/new.html.twig', [
			'borrow' => $borrow,
			'form' => $form->createView()
		]);
	}

	/**
	 *
	 * @Route("/admin/show/borrow/{id}", name="borrow_show", methods={"GET"})
	 */
	public function show(Borrow $borrow): Response{
		return $this->render('borrow/show.html.twig', [
			'borrow' => $borrow
		]);
	}

	/**
	 *
	 * @Route("/admin/edit/borrow/{id}/edit", name="borrow_edit", methods={"GET","POST"})
	 */
	public function edit(Request $request, Borrow $borrow, BorrowService $borrowService): Response{
		$form = $this->createForm(BorrowType::class, $borrow);
		$form->handleRequest($request);
		$borrow_duration = $borrow->getBorrowDuration();
		if ($form->isSubmitted()){
			if ($form->isValid()){
				try{
					$redirectToList = true;
					if (!$borrowService->checkExpiryDateValidity($borrow)){
						$this->addFlash(
							'warning',
							sprintf("Les données ont été enregistrées, mais la date d'échéance du prêt n'est pas %d jour(s) après la date d'emprunt (durée normalement prédéfinie pour tous les types de médias : %s",
								$borrow->getBorrowDuration(),
								$borrow->getStockableMediaCopy()
									->getStockableMedia()
									->getMedia()
									->getMediaType()
									->getName()
							)
						);
						$redirectToList = false;
					}
					$this->getDoctrine()->getManager()->flush();
		
					$this->addFlash('success', 'Données modifiées avec succès');
					if ($redirectToList)
						return $this->redirectToRoute('borrow_index');
				}catch(\Exception $ex){
					$this->addFlash('error', 'Une erreur est survenue lors du traitement des données');
					throw $ex;
				}
			}else{
				$this->addFlash('warning', 'Certaines informations sont invalides');
			}
		}

		return $this->render('borrow/edit.html.twig', [
			'borrow' => $borrow,
			'form' => $form->createView(),
			'borrow_duration' => $borrow_duration
		]);
	}

	/**
	 *
	 * @Route("/admin/delete/borrow/{id}", name="borrow_delete", methods={"DELETE"})
	 */
	public function delete(Request $request, Borrow $borrow): Response{
		if ($this->isCsrfTokenValid('delete' . $borrow->getId(), $request->request->get('_token'))){
			$entityManager = $this->getDoctrine()->getManager();
			$entityManager->remove($borrow);
			$entityManager->flush();
			$this->addFlash('success', 'Données supprimées avec succès');
		}else{
			$this->addFlash('warning', 'Une erreur est survenue lors de la suppression des données');
		}

		return $this->redirectToRoute('borrow_index');
	}
}
