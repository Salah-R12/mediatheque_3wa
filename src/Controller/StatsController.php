<?php

namespace App\Controller;

use App\Repository\MediaRepository;
use App\Repository\BorrowRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class StatsController extends AbstractController
{

    public function lastMedias(MediaRepository $mediaRepository)
    {
        return $this->render('stats/last_medias.html.twig', [
            "last_medias"=>$mediaRepository->findLast(10)
        ]);
    }

    public function lastBorrows(BorrowRepository $borrowRepository)
    {
        return $this->render('stats/last_borrows.html.twig', [
            "last_borrows"=>$borrowRepository->findLast(10)
        ]);
    }
    public function lateBorrowsMedia (BorrowRepository $borrowRepository){
        return $this->render('stats/late_borrows_media.html.twig', [
            "late_borrows"=>$borrowRepository->lateBorrows()
        ]);
    }
}
