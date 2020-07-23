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

/**
 * @Route("/music")
 */
class MusicController extends AbstractController
{
    /**
     * @Route("/", name="music_index", methods={"GET"})
     */
    public function index(MusicRepository $musicRepository): Response
    {
        return $this->render('music/index.html.twig', [
            'music' => $musicRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="music_new", methods={"GET","POST"})
     */
    public function new(Request $request, StockableMediaCopyService $stockableCopyService): Response
    {
        $music = new Music();
        $form = $this->createForm(MusicType::class, $music);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $mediaType = $this->getDoctrine()->getRepository(MediaType::class)->findOneBy(['name' => 'Music']);
                $music->setMediaType($mediaType);
                $stockableCopyService->generateCopyFromMedia($music->getStockableMedia());

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($music);
                $entityManager->flush();
                $this->addFlash("success", "New music is added to database with success");

                return $this->redirectToRoute('music_index');
            }
            else {
                $this->addFlash("warning", "an error occured");
            }
        }

        return $this->render('music/new.html.twig', [
            'music' => $music,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="music_show", methods={"GET"})
     */
    public function show(Music $music): Response
    {
        return $this->render('music/show.html.twig', [
            'music' => $music,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="music_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Music $music, StockableMediaCopyService $stockableCopyService): Response
    {
        $form = $this->createForm(MusicType::class, $music);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $stockableCopyService->generateCopyFromMedia($music->getStockableMedia());
                $this->getDoctrine()->getManager()->flush();
                $this->addFlash("success", "The music is edited in database with success");

                return $this->redirectToRoute('music_index');
            }
            else{
                $this->addFlash("warning", "Error: Edit process isn't finished");
            }
        }

        return $this->render('music/edit.html.twig', [
            'music' => $music,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="music_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Music $music): Response
    {
        if ($this->isCsrfTokenValid('delete'.$music->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($music);
            $entityManager->flush();
        }

        return $this->redirectToRoute('music_index');
    }

}
