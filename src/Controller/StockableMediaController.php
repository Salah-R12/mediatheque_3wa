<?php

namespace App\Controller;

use App\Entity\StockableMedia;
use App\Form\StockableMediaType;
use App\Repository\StockableMediaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class StockableMediaController extends AbstractController
{
    public function index(StockableMediaRepository $stockableMediaRepository): Response
    {
        return $this->render('stockable_media/index.html.twig', [
            'stockable_media' => $stockableMediaRepository->findAll(),
        ]);
    }

    public function new(Request $request): Response
    {
        $stockableMedia = new StockableMedia();
        $form = $this->createForm(StockableMediaType::class, $stockableMedia);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($stockableMedia);
            $entityManager->flush();

            return $this->redirectToRoute('stockable_media_index');
        }

        return $this->render('stockable_media/new.html.twig', [
            'stockable_media' => $stockableMedia,
            'form' => $form->createView(),
        ]);
    }

    public function show(StockableMedia $stockableMedia): Response
    {
        return $this->render('stockable_media/show.html.twig', [
            'stockable_media' => $stockableMedia,
        ]);
    }

    public function edit(Request $request, StockableMedia $stockableMedia): Response
    {
        $form = $this->createForm(StockableMediaType::class, $stockableMedia);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('stockable_media_index');
        }

        return $this->render('stockable_media/edit.html.twig', [
            'stockable_media' => $stockableMedia,
            'form' => $form->createView(),
        ]);
    }

    public function delete(Request $request, StockableMedia $stockableMedia): Response
    {
        if ($this->isCsrfTokenValid('delete'.$stockableMedia->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($stockableMedia);
            $entityManager->flush();
        }

        return $this->redirectToRoute('stockable_media_index');
    }
}
