<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\MediaRepository;


class SearchController extends AbstractController
{
    /**
     * @Route("/search", name="search")
     */
    public function index(Request $request, MediaRepository $mediaRepository)
    {
        $query = $request->get('research')['research'];

        return $this->render('search/index.html.twig', [
            'controller_name' => 'SearchController',
            'results' => $mediaRepository->findByname($query)
        ]);
    }
}
