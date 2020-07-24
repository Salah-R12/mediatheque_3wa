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

/**
 * @Route("/film")
 */
class FilmController extends AbstractController
{
    /**
     * @Route("/", name="film_index", methods={"GET"})
     */
    public function index(FilmRepository $filmRepository): Response
    {
        return $this->render('film/index.html.twig', [
            'films' => $filmRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="film_new", methods={"GET","POST"})
     */
    public function new(Request $request, StockableMediaCopyService $stockableCopyService): Response
    {
        $film = new Film();
        $form = $this->createForm(FilmType::class, $film);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $film_media = $this->getDoctrine()->getRepository(MediaType::class)->findOneBy(['name' => 'Film']);
            $film->setMediaType($film_media);
            $stockableCopyService->generateCopyFromMedia($film->getStockableMedia());
            
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($film);
            $entityManager->flush();

            $this->addFlash(
                'success',
                'Your new film were saved!'
            );
            return $this->redirectToRoute('film_index');
        }

        if ($form->isSubmitted()) {
            $this->addFlash(
                'warning',
                'Your new member is nat saved!'
            );
        }

        return $this->render('film/new.html.twig', [
            'film' => $film,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="film_show", methods={"GET"})
     */
    public function show(Film $film): Response
    {
        return $this->render('film/show.html.twig', [
            'film' => $film,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="film_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Film $film, StockableMediaCopyService $stockableCopyService): Response
    {
        $form = $this->createForm(FilmType::class, $film);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
        	$stockableCopyService->generateCopyFromMedia($film->getStockableMedia());
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('film_index');
        }

        return $this->render('film/edit.html.twig', [
            'film' => $film,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="film_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Film $film): Response
    {
        if ($this->isCsrfTokenValid('delete'.$film->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($film);
            $entityManager->flush();
        }

        return $this->redirectToRoute('film_index');
    }
}
