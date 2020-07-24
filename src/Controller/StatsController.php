<?php

namespace App\Controller;

use App\Repository\MediaRepository;
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

    public function lastMedias(MediaRepository $mediaRepository){

        return $this->render('stats/last_medias.html.twig', [
            "last_medias"=>$mediaRepository->findLast(10)
        ]);
    }
}
