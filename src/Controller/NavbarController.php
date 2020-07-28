<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\Navigation;
use Symfony\Component\HttpFoundation\Request;
use App\Form\ResearchType;

class NavbarController extends AbstractController
{
	/**
	 * 
	 * @param Navigation $nav
	 * @param string $current_route_name It is passed from template, to give the parent controller route name (since here, current route name would be in this controller, actually not defined)
	 * @param Request $request
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
    public function index(Navigation $nav, string $current_route_name)
    {
		// TODO defined roles: for instance, all is public
		
		$form = $this->createForm(ResearchType::class, [
            'action' => $this->generateUrl('search')
		]);
		
    	$userRoleID = null;
    	$navbar_links = $nav->getNavBarLinks($userRoleID);
    	$homepage_link = [];
    	foreach ($navbar_links as $ix => $item){
    		if (!empty($item['is_homepage'])){
    			$homepage_link = $item;
    			unset($navbar_links[$ix]);
    			break;
    		}
		}
		// $form->handleRequest($request);
        // if($form->isSubmitted() && $form->isValid())
        // {
        //     var_dump($request); die;
        // }
	    return $this->render('navbar/index.html.twig', [
            'controller_name' => 'NavbarController',
        	'navbar_links' => $navbar_links,
        	'home_page' => $homepage_link,
			'current_route_name'  => $current_route_name,
	
			'form' => $form->CreateView()
        ]);
    }
}
