<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;


class SearchController extends AbstractController
{
    /**
     * @Route("/search", name="search")
     */
    public function index(Request $request)
    {
        return $this->render('search/index.html.twig', [
            'controller_name' => 'SearchController',
            'request' => $request
        ]);
    }
}
