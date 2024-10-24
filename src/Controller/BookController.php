<?php

namespace App\Controller;

use App\Entity\Book;
use App\Form\BookType;
use App\Repository\BookRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class BookController extends AbstractController
{
    #[Route('/fetchBook', name: 'fetchBook')]
    public function fetchBook(BookRepository $rep): Response
    {
        $books = $rep->findAll();
        return $this->render('book/bookList.html.twig', [
            'books' => $books,
        ]);
    }

    #[Route('/addBook', name: 'addBook')]
    public function addBook(BookRepository $rep, ManagerRegistry $rm, Request $req): Response
    {
        $b=new Book;
        $form=$this->createForm(BookType::class,$b);
        $form->handleRequest($req);
        if($form->isSubmitted() ){
            $em=$rm->getManager();
            $em->persist($b);
            $em->flush($b);
            return $this->redirectToRoute('fetchBook');

        }
        return $this->render('book/add.html.twig',[
            'form'=>$form->createView()
        ]);
        
    }
}
