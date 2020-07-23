<?php

namespace App\Controller;

use App\Entity\Book;
use App\Entity\MediaType;
use App\Form\BookType;
use App\Repository\BookRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\Entity\StockableMediaCopy as StockableMediaCopyService;

/**
 * @Route("/book")
 */
class BookController extends AbstractController
{
    /**
     * @Route("/", name="book_index", methods={"GET"})
     */
    public function index(BookRepository $bookRepository): Response
    {
        return $this->render('book/index.html.twig', [
            'books' => $bookRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="book_new", methods={"GET","POST"})
     */
    public function new(Request $request, StockableMediaCopyService $stockableCopyService): Response
    {
        $book = new Book();
        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
        	if ($form->isValid()){
        		try{
	            $mediaType = $this->getDoctrine()->getRepository(MediaType::class)->findOneBy(['name' => 'Book']);
	            $book->setMediaType($mediaType);
	            $stockableCopyService->generateCopyFromMedia($book->getStockableMedia());
	            $entityManager = $this->getDoctrine()->getManager();
	            $entityManager->persist($book);
	            $entityManager->flush();
	
	            $this->addFlash('success', 'Données enregistrées avec succès');
	            
	            return $this->redirectToRoute('book_index');
        		}catch(\Exception $ex){
        			$this->addFlash('warning', 'Une erreur s\'est produite lors de l\'enregistrement');
        			throw $ex;
        		}
        	}else{
        		$this->addFlash('warning', 'Certaines informations saisies ne sont pas correctes');
        	}
        }

        return $this->render('book/new.html.twig', [
            'book' => $book,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="book_show", methods={"GET"})
     */
    public function show(Book $book): Response
    {
        return $this->render('book/show.html.twig', [
            'book' => $book,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="book_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Book $book, StockableMediaCopyService $stockableCopyService): Response
    {
        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
        	if ($form->isValid()){
	        	try{
		        	$stockableCopyService->generateCopyFromMedia($book->getStockableMedia());
		         	$this->getDoctrine()->getManager()->flush();
					
		         	$this->addFlash('success', 'Données modifiées avec succès');
		         	
		            return $this->redirectToRoute('book_index');
	        	}catch(\Exception $ex){
	        		$this->addFlash('warning', 'Une erreur s\'est produite lors de l\'enregistrement');
	        		throw $ex;
	        	}
        	}else{
        		$this->addFlash('warning', 'Certaines informations saisies ne sont pas correctes');
        	}
        }

        return $this->render('book/edit.html.twig', [
            'book' => $book,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="book_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Book $book): Response
    {
        if ($this->isCsrfTokenValid('delete'.$book->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($book);
            $entityManager->flush();
        }

        return $this->redirectToRoute('book_index');
    }
}
