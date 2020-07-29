<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\Session\Session;

class SecurityMemberController extends AbstractController
{
    /**
     * @Route("/login/member", name="member_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.member.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/logout/member", name="member_logout")
     */
    public function logout()
    {
    	// TODO: unset user session and then, redirect to landing page
    	/*$session = $this->get('session');
    	$session = new Session();
    	$session->invalidate();
    	//$this->getUser();
    	$this->
    	$this->addFlash('notice', 'Session fermée');*/
    	return $this->redirectToRoute('dashboard');
        //throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
