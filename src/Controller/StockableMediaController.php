<?php

namespace App\Controller;

use App\Entity\StockableMedia;
use App\Form\StockableMedia1Type;
use App\Repository\StockableMediaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/stockable/media")
 */
class StockableMediaController extends AbstractController
{
    /**
     * @Route("/", name="stockable_media_index", methods={"GET"})
     */
    public function index(StockableMediaRepository $stockableMediaRepository): Response
    {
        return $this->render('stockable_media/index.html.twig', [
            'stockable_media' => $stockableMediaRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="stockable_media_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $stockableMedia = new StockableMedia();
        $form = $this->createForm(StockableMedia1Type::class, $stockableMedia);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            print_r($stockableMedia);exit;
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

    /**
     * @Route("/{id}", name="stockable_media_show", methods={"GET"})
     */
    public function show(StockableMedia $stockableMedia): Response
    {
        return $this->render('stockable_media/show.html.twig', [
            'stockable_media' => $stockableMedia,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="stockable_media_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, StockableMedia $stockableMedia): Response
    {
        $form = $this->createForm(StockableMedia1Type::class, $stockableMedia);
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

    /**
     * @Route("/{id}", name="stockable_media_delete", methods={"DELETE"})
     */
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
