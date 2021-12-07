<?php

namespace App\Controller;

use App\Entity\Book;
use App\Repository\BookRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class BookController extends AbstractController
{
    /**
     * @Route ("/books", name="books")
     */
    public function books(BookRepository $bookRepository)
    {
        $books = $bookRepository->findAll();

        return $this->render("books.html.twig",['books'=> $books]);
    }


    /**
     * @Route("/book/create", name="book_create")
     */
    public function createBook()
    {
        $book = new Book();
        $book->setTitle("Les thanatonautes");
        $book->setAuthor("Bernard Werber");
        $book->setNbPages("700");
        $book->setPublishedAt(new \DateTime('1995-12-12'));

        dump($book); die;

        // enregistrer l'instance de la classe Book (l'entité) en BDD
    }

    /**
     * @Route("/book/{id}", name="book")
     */
    public function book($id, BookRepository $bookRepository)
    {
        $book = $bookRepository->find($id);

        return $this->render("book.html.twig",['book'=> $book]);
    }



}
