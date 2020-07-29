<?php
namespace App\Controller;
use App\Entity\MediaType;
use App\Entity\Music;
use App\Form\MusicType;
use App\Repository\MusicRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\Entity\StockableMediaCopy as StockableMediaCopyService;

class MusicController extends AbstractController{

	/**
	 *
	 * @Route("/list/music", name="music_index", methods={"GET"})
	 */
	public function index(MusicRepository $musicRepository): Response{
		return $this->render('music/index.html.twig', [
			'music' => $musicRepository->findAll()
		]);
	}

	/**
	 *
	 * @Route("/admin/new/music", name="music_new", methods={"GET","POST"})
	 */
	public function new(Request $request, StockableMediaCopyService $stockableCopyService): Response{
		$music = new Music();
		$form = $this->createForm(MusicType::class, $music);
		$form->handleRequest($request);

		if ($form->isSubmitted()){
			if ($form->isValid()){
				$mediaType = $this->getDoctrine()
					->getRepository(MediaType::class)
					->findOneBy([
					'name' => 'Music'
				]);
				$music->setMediaType($mediaType);
				$stockableCopyService->generateCopyFromMedia($music->getStockableMedia());

				$entityManager = $this->getDoctrine()->getManager();
				$entityManager->persist($music);
				$entityManager->flush();
				$this->addFlash("success", "New music is added to database with success");

				return $this->redirectToRoute('music_index');
			} else{
				$this->addFlash("warning", "an error occured");
			}
		}

		return $this->render('music/new.html.twig', [
			'music' => $music,
			'form' => $form->createView()
		]);
	}

	/**
	 *
	 * @Route("/show/music/{id}", name="music_show", methods={"GET"})
	 */
	public function show(Music $music): Response{
		return $this->render('music/show.html.twig', [
			'music' => $music
		]);
	}

	/**
	 *
	 * @Route("/admin/edit/music/{id}", name="music_edit", methods={"GET","POST"})
	 */
	public function edit(Request $request, Music $music, StockableMediaCopyService $stockableCopyService): Response{
		$form = $this->createForm(MusicType::class, $music);
		$form->handleRequest($request);

		if ($form->isSubmitted()){
			if ($form->isValid()){
				try{
					$copyNumberFailedToBeRemoved = $stockableCopyService->handleMediaEdition($music);
					// Here, we can flush some data even if "$editSucceeded" is false (@see: function definition of "handleMediaEdition")
					$this->getDoctrine()
						->getManager()
						->flush();

					if (!$copyNumberFailedToBeRemoved){
						$this->addFlash('success', 'Données modifiées avec succès');
						return $this->redirectToRoute('music_index');
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

		return $this->render('music/edit.html.twig', [
			'music' => $music,
			'form' => $form->createView()
		]);
	}

	/**
	 *
	 * @Route("/admin/delete/music/{id}", name="music_delete", methods={"DELETE"})
	 */
	public function delete(Request $request, Music $music): Response{
		if ($this->isCsrfTokenValid('delete' . $music->getId(), $request->request->get('_token'))){
			$entityManager = $this->getDoctrine()->getManager();
			$entityManager->remove($music);
			$entityManager->flush();
		}

		return $this->redirectToRoute('music_index');
	}
}
