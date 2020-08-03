<?php
namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class LoginController extends AbstractController{

	/**
	 * Cette page de login va inclure les 2 autres controllers: SecurityMemberController et SecurityStaffController
	 * dans la template twig avec un render(controller())
	 *
	 * @Route("/login", name="login_index")
	 */
	public function index(){
		if ($this->getUser()){
			// Si on est déjà loggué, tenter d'accéder à la page de login renvoie à la dashboard
			return $this->redirectToRoute('dashboard');
		}
		return $this->render('login/index.html.twig');
	}

	/**
	 * En principe, cette fonction routée est interceptée par Symfony car on a configuré security.yaml afin que Symfony intercepte cette fonction pour exécuter son propre système de logout.
	 * Donc, dans ce cas, il est possible de laisser cette fonction vide.
	 *
	 * @Route("/logout", name="logout")
	 */
	public function logout(){
		// retour à la page login après le logout (si jamais la config security.yaml change, mais en principe, on peut laisser vide cette fonction
		return $this->redirectToRoute('login_index');
	}
}