<?php

namespace App\Controller;

use App\Entity\Staff;
use App\Form\StaffNewType;
use App\Form\StaffType;
use App\Repository\StaffRepository;
use App\Service\SendMails;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StaffController extends AbstractController
{
    /**
     * @Route("/admin/list/staff", name="staff_index", methods={"GET"})
     */
    public function index(StaffRepository $staffRepository): Response
    {
        return $this->render('staff/index.html.twig', [
            'staff' => $staffRepository->findAll(),
        ]);
    }

    /**
     * @Route("/admin/new/staff", name="staff_new", methods={"GET","POST"})
     */
    public function new(Request $request, SendMails $sendMails): Response
    {
        $staff = new Staff();
        $form = $this->createForm(StaffNewType::class, $staff);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
        	if ($form->isValid()){
        	    $staff->setActivationToken(md5(uniqid()));
                $staffToken = $staff->getActivationToken();
        	    $staffMail = $form->get("email")->getData();
                // $emailService   = $this->container->get('sendmails');
                // On crée le message
	            $entityManager = $this->getDoctrine()->getManager();
	            $entityManager->persist($staff);
	            $entityManager->flush();
                $sendMails->sendMailToNewStaff($staffMail, $staffToken);
	            $this->addFlash('success', 'Données enregistrées avec succès');
	            return $this->redirectToRoute('staff_index');
        	}else{
        		$this->addFlash('warning', 'Certaines informations sont invalides ou manquantes');
        	}
        }

        return $this->render('staff/new.html.twig', [
            'staff' => $staff,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/show/{id}", name="staff_show", methods={"GET"})
     */
    public function show(Staff $staff): Response
    {
        return $this->render('staff/show.html.twig', [
            'staff' => $staff,
        ]);
    }

    /**
     * @Route("/admin/edit/staff/{id}", name="staff_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Staff $staff): Response
    {
        $form = $this->createForm(StaffType::class, $staff);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
        	if ($form->isValid()){
	            $this->getDoctrine()->getManager()->flush();
	
	            $this->addFlash('success', 'Données enregistrées avec succès');
	            return $this->redirectToRoute('staff_index');
        	}else{
        		$this->addFlash('warning', 'Certaines informations sont invalides ou manquantes');
        	}
        }

        return $this->render('staff/edit.html.twig', [
            'staff' => $staff,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/delete/staff/{id}", name="staff_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Staff $staff): Response
    {
        if ($this->isCsrfTokenValid('delete'.$staff->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($staff);
            $entityManager->flush();
        }

        return $this->redirectToRoute('staff_index');
    }

    /**
     * @Route("/activation/{token}", name="activation")
     */
    public function activation($token, StaffRepository $staffRepository)
    {
        // On recherche si un staff avec ce token existe dans la base de données
        $staff = $staffRepository->findOneBy(['activation_token' => $token]);

        // Si aucun utilisateur n'est associé à ce token
        if(!$staff){
            // On renvoie une erreur 404
            throw $this->createNotFoundException('Cet utilisateur n\'existe pas');
        }

        // On supprime le token
        $staff->setActivationToken(null);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($staff);
        $entityManager->flush();

        // On génère un message
        $this->addFlash('message', 'Utilisateur activé avec succès');

        // On retourne à l'accueil
        return $this->redirectToRoute('dashboard');
    }
}
