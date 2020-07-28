<?php

namespace App\Controller;

use App\Entity\Stream;
use App\Form\StreamType;
use App\Repository\StreamRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StreamController extends AbstractController
{
    /**
     * @Route("/admin/list/stream", name="stream_index", methods={"GET"})
     */
    public function index(StreamRepository $streamRepository): Response
    {
        return $this->render('stream/index.html.twig', [
            'streams' => $streamRepository->findAll(),
        ]);
    }

    /**
     * @Route("/admin/new/stream", name="stream_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $stream = new Stream();
        $form = $this->createForm(StreamType::class, $stream);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($stream);
            $entityManager->flush();

            return $this->redirectToRoute('stream_index');
        }

        return $this->render('stream/new.html.twig', [
            'stream' => $stream,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/show/stream/{id}", name="stream_show", methods={"GET"})
     */
    public function show(Stream $stream): Response
    {
        return $this->render('stream/show.html.twig', [
            'stream' => $stream,
        ]);
    }

    /**
     * @Route("/admin/edit/stream/{id}", name="stream_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Stream $stream): Response
    {
        $form = $this->createForm(StreamType::class, $stream);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('stream_index');
        }

        return $this->render('stream/edit.html.twig', [
            'stream' => $stream,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/delete/stream/{id}", name="stream_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Stream $stream): Response
    {
        if ($this->isCsrfTokenValid('delete'.$stream->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($stream);
            $entityManager->flush();
        }

        return $this->redirectToRoute('stream_index');
    }
}
