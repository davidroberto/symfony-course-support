<?php

namespace App\Controller;

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
     * @Route ("/book/{id}", name="book")
     */
    public function book($id, BookRepository $bookRepository)
    {
        $book = $bookRepository->find($id);
        return $this->render("book.html.twig",['book'=> $book]);
    }

}
