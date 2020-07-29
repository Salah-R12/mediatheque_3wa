<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/login")
 */
class LoginController extends AbstractController
{
    /**
     * @Route("/member", name="login_member", methods={"GET","POST"})
     */
    public function member(Request $request)
    {
        return $this->render('login/member.html.twig', [

        ]);
    }

    /**
     * @Route("/staff", name="login_staff", methods={"GET","POST"})
     */
    public function staff(Request $request)
    {
        return $this->render('login/staff.html.twig', [

        ]);
    }
}
