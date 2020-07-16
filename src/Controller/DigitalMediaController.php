<?php

namespace App\Controller;

use App\Entity\DigitalMedia;
use App\Form\DigitalMediaType;
use App\Repository\DigitalMediaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class DigitalMediaController extends AbstractController
{
    public function index(DigitalMediaRepository $digitalMediaRepository): Response
    {
        return $this->render('digital_media/index.html.twig', [
            'digital_media' => $digitalMediaRepository->findAll(),
        ]);
    }

     public function new(Request $request): Response
    {
        $digitalMedia = new DigitalMedia();
        $form = $this->createForm(DigitalMediaType::class, $digitalMedia);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($digitalMedia);
            $entityManager->flush();

            return $this->redirectToRoute('digital_media_index');
        }

        return $this->render('digital_media/new.html.twig', [
            'digital_media' => $digitalMedia,
            'form' => $form->createView(),
        ]);
    }

    public function show(DigitalMedia $digitalMedia): Response
    {
        return $this->render('digital_media/show.html.twig', [
            'digital_media' => $digitalMedia,
        ]);
    }

    public function edit(Request $request, DigitalMedia $digitalMedia): Response
    {
        $form = $this->createForm(DigitalMediaType::class, $digitalMedia);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('digital_media_index');
        }

        return $this->render('digital_media/edit.html.twig', [
            'digital_media' => $digitalMedia,
            'form' => $form->createView(),
        ]);
    }

    public function delete(Request $request, DigitalMedia $digitalMedia): Response
    {
        if ($this->isCsrfTokenValid('delete'.$digitalMedia->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($digitalMedia);
            $entityManager->flush();
        }

        return $this->redirectToRoute('digital_media_index');
    }
}
