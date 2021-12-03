<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class LibraryController extends AbstractController
{

    /**
     * @Route ("/", name="home")
     */
    public function home()
    {
        $array = array_slice($this->books, -3);

        return $this->render("home.html.twig", ['home'=> $array]);
    }

    /**
     * @Route ("/books", name="books")
     */
    public function books()
    {


        return $this->render("books.html.twig",['books'=> $books]);
    }

    /**
     * @Route ("/book/{id}", name="book")
     */
    public function book($id)
    {
        return $this->render("book.html.twig",['book'=> $this->books[$id]]);
    }

}
