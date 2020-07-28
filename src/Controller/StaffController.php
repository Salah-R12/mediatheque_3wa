<?php

namespace App\Controller;

use App\Entity\Staff;
use App\Form\StaffNewType;
use App\Form\StaffType;
use App\Repository\StaffRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/staff")
 */
class StaffController extends AbstractController
{
    /**
     * @Route("/", name="staff_index", methods={"GET"})
     */
    public function index(StaffRepository $staffRepository): Response
    {
        return $this->render('staff/index.html.twig', [
            'staff' => $staffRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="staff_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $staff = new Staff();
        $form = $this->createForm(StaffNewType::class, $staff);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
        	if ($form->isValid()){
	            $entityManager = $this->getDoctrine()->getManager();
	            $entityManager->persist($staff);
	            $entityManager->flush();
	
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
     * @Route("/{id}", name="staff_show", methods={"GET"})
     */
    public function show(Staff $staff): Response
    {
        return $this->render('staff/show.html.twig', [
            'staff' => $staff,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="staff_edit", methods={"GET","POST"})
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
     * @Route("/{id}", name="staff_delete", methods={"DELETE"})
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
}
