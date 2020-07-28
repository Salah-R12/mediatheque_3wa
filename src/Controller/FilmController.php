<?php
namespace App\Controller;
use App\Entity\Film;
use App\Entity\MediaType;
use App\Form\FilmType;
use App\Repository\FilmRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\Entity\StockableMediaCopy as StockableMediaCopyService;

class FilmController extends AbstractController{

	/**
	 *
	 * @Route("/list/film", name="film_index", methods={"GET"})
	 */
	public function index(FilmRepository $filmRepository): Response{
		return $this->render('film/index.html.twig', [
			'films' => $filmRepository->findAll()
		]);
	}

	/**
	 *
	 * @Route("/admin/new/film", name="film_new", methods={"GET","POST"})
	 */
	public function new(Request $request, StockableMediaCopyService $stockableCopyService): Response{
		$film = new Film();
		$form = $this->createForm(FilmType::class, $film);
		$form->handleRequest($request);
		if ($form->isSubmitted() && $form->isValid()){
			$film_media = $this->getDoctrine()
				->getRepository(MediaType::class)
				->findOneBy([
				'name' => 'Film'
			]);
			$film->setMediaType($film_media);
			$stockableCopyService->generateCopyFromMedia($film->getStockableMedia());

			$entityManager = $this->getDoctrine()->getManager();
			$entityManager->persist($film);
			$entityManager->flush();

			$this->addFlash('success', 'Your new film were saved!');
			return $this->redirectToRoute('film_index');
		}

		if ($form->isSubmitted()){
			$this->addFlash('warning', 'Your new member is nat saved!');
		}

		return $this->render('film/new.html.twig', [
			'film' => $film,
			'form' => $form->createView()
		]);
	}

	/**
	 *
	 * @Route("/show/film/{id}", name="film_show", methods={"GET"})
	 */
	public function show(Film $film): Response{
		return $this->render('film/show.html.twig', [
			'film' => $film
		]);
	}

	/**
	 *
	 * @Route("/admin/edit/film/{id}", name="film_edit", methods={"GET","POST"})
	 */
	public function edit(Request $request, Film $film, StockableMediaCopyService $stockableCopyService): Response{
		$form = $this->createForm(FilmType::class, $film);
		$form->handleRequest($request);

		if ($form->isSubmitted()){
			if ($form->isValid()){
				try{
					$copyNumberFailedToBeRemoved = $stockableCopyService->handleMediaEdition($film);
					// Here, we can flush some data even if "$editSucceeded" is false (@see: function definition of "handleMediaEdition")
					$this->getDoctrine()
						->getManager()
						->flush();

					if (!$copyNumberFailedToBeRemoved){
						$this->addFlash('success', 'Données modifiées avec succès');
						return $this->redirectToRoute('film_index');
					} else{
						$this->addFlash('warning', sprintf('Attention, le système a détecté qu\'à partir de l\'exemplaire %d, le livre a été emprunté. Seuls les numéros d\'exemplaires au-dessus de ce numéro et n\'ayant jamais été emprunté seront supprimés', $copyNumberFailedToBeRemoved));
						$this->addFlash('notice', sprintf('Les données ont été partiellement mises à jour. Le nombre d\'exemplaires reste à %d', $copyNumberFailedToBeRemoved));
					}
				} catch (\Exception $ex){
					$this->addFlash('warning', 'Une erreur s\'est produite lors de l\'enregistrement');
					throw $ex;
				}
			} else{
				$this->addFlash('warning', 'Certaines informations saisies ne sont pas correctes');
			}
		}

		return $this->render('film/edit.html.twig', [
			'film' => $film,
			'form' => $form->createView()
		]);
	}

	/**
	 *
	 * @Route("/admin/delete/film/{id}", name="film_delete", methods={"DELETE"})
	 */
	public function delete(Request $request, Film $film): Response{
		if ($this->isCsrfTokenValid('delete' . $film->getId(), $request->request->get('_token'))){
			$entityManager = $this->getDoctrine()->getManager();
			$entityManager->remove($film);
			$entityManager->flush();
		}

		return $this->redirectToRoute('film_index');
	}
}
