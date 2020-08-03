<?php
namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\Session\Session;

class SecurityMemberController extends AbstractController{

	/**
	 *
	 * @Route("/login/member", name="member_login")
	 */
	public function login(AuthenticationUtils $authenticationUtils, bool $included_as_block = false): Response{
		// get the login error if there is one
		$error = $authenticationUtils->getLastAuthenticationError();
		// last username entered by the user
		$lastUsername = $authenticationUtils->getLastUsername();

		return $this->render('security/login.member.html.twig', [
			'last_username' => $lastUsername,
			'error' => $error,
			'included_as_block' => $included_as_block
		]);
	}

	/**
	 *
	 * @Route("/logout/member", name="member_logout")
	 */
	public function logout(){
		// Renvoie vers la page principale de logout gérée par le controller "LoginController" (c'est cet URL /logout de base qui est configuré dans security.yaml pour être intercepté par Symfony et exécuter le logout du système)
		return $this->redirectToRoute('logout');
	}
}
