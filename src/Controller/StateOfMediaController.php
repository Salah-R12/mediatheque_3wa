<?php

namespace App\Controller;

use App\Entity\StateOfMedia;
use App\Form\StateOfMediaType;
use App\Repository\StateOfMediaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/state/of/media")
 */
class StateOfMediaController extends AbstractController
{
    /**
     * @Route("/", name="state_of_media_index", methods={"GET"})
     */
    public function index(StateOfMediaRepository $stateOfMediaRepository): Response
    {
        return $this->render('state_of_media/index.html.twig', [
            'state_of_media' => $stateOfMediaRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="state_of_media_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $stateOfMedia = new StateOfMedia();
        $form = $this->createForm(StateOfMediaType::class, $stateOfMedia);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($stateOfMedia);
            $entityManager->flush();

            return $this->redirectToRoute('state_of_media_index');
        }

        return $this->render('state_of_media/new.html.twig', [
            'state_of_media' => $stateOfMedia,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="state_of_media_show", methods={"GET"})
     */
    public function show(StateOfMedia $stateOfMedia): Response
    {
        return $this->render('state_of_media/show.html.twig', [
            'state_of_media' => $stateOfMedia,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="state_of_media_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, StateOfMedia $stateOfMedia): Response
    {
        $form = $this->createForm(StateOfMediaType::class, $stateOfMedia);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('state_of_media_index');
        }

        return $this->render('state_of_media/edit.html.twig', [
            'state_of_media' => $stateOfMedia,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="state_of_media_delete", methods={"DELETE"})
     */
    public function delete(Request $request, StateOfMedia $stateOfMedia): Response
    {
        if ($this->isCsrfTokenValid('delete'.$stateOfMedia->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($stateOfMedia);
            $entityManager->flush();
        }

        return $this->redirectToRoute('state_of_media_index');
    }
}
