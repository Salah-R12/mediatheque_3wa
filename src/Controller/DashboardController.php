<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    /**
     * @Route("/dashboard", name="dashboard")
     */
    public function index()
    {

        $DatabaseEm1 = $this->getDoctrine()->getManager();
        $DatabaseEm2 = $this->getDoctrine()->getManager();
        $DatabaseEm3 = $this->getDoctrine()->getManager();

        // Write your raw SQL
        $last_books = 'SELECT t1.id, t2.name FROM book t1 INNER JOIN media t2 WHERE t1.id = t2.id ORDER BY ID DESC LIMIT 5;';
        $last_films = 'SELECT id, original_title FROM film ORDER BY ID DESC LIMIT 5;';
        $last_musics = 'SELECT ID, titles FROM music ORDER BY ID DESC LIMIT 5;';
        $last_borrows = 'SELECT t1.id, t1.borrow_date, t1.expiry_date, t2.username, t2.id FROM borrow t1 INNER JOIN member t2 WHERE t1.member_id = t2.id ORDER BY t1.id DESC LIMIT 5';

        // Prepare the query from DATABASE1
        $statementDB1 = $DatabaseEm1->getConnection()->prepare($last_books);

        // Prepare the query from DATABASE2
        $statementDB2 = $DatabaseEm2->getConnection()->prepare($last_films);

        // Prepare the query from DATABASE3
        $statementDB3 = $DatabaseEm2->getConnection()->prepare($last_musics);

        // Prepare the query from DATABASE4
        $statementDB4 = $DatabaseEm2->getConnection()->prepare($last_borrows);

        // Execute queries
        $statementDB1->execute();
        $statementDB2->execute();
        $statementDB3->execute();
        $statementDB4->execute();


        // Get results :)
        $resultDatabase1 = $statementDB1->fetchAll();
        $resultDatabase2 = $statementDB2->fetchAll();
        $resultDatabase3 = $statementDB3->fetchAll();
        $resultDatabase4 = $statementDB3->fetchAll();

        $em = $this->getDoctrine()->getManager();
        return $this->render('dashboard/index.html.twig', [
            'controller_name' => 'DashboardController',
            'last_books' => $resultDatabase1,
            'last_films' => $resultDatabase2,
            'last_musics' => $resultDatabase3,
            'last_borrows' => $resultDatabase4
        ]);
    }
}
