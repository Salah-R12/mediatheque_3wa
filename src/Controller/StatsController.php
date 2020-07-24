<?php

namespace App\Controller;

use App\Repository\BookRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class StatsController extends AbstractController
{
    /**
     * @Route("/stats", name="stats")
     */
    public function index()
    {
        return $this->render('stats/index.html.twig', [
            'controller_name' => 'StatsController',
        ]);
    }

    public function lastBooks(BookRepository $bookRepository){

        return $this->render('stats/last_books.html.twig', [
            "last_books"=>$bookRepository->findLastBooks(5)
          ]);
    }
}
