<?php

namespace App\Controller;

use App\Entity\Book;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
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
    public function createBook(EntityManagerInterface $entityManager)
    {
        // Je créé une nouvelle instance de la classe Book (de l'entité Book)
        // dans le but de l'enregistrer en bdd
        // Doctrine prendra l'entité avec les valeurs de chacune des propriétés
        // et créera un enregistrement dans la table Book
        $book = new Book();
        $book->setTitle("Les thanatonautes");
        $book->setAuthor("Bernard Werber");
        $book->setNbPages(700);
        $book->setPublishedAt(new \DateTime('1995-12-12'));

        // une fois l'entité créée, j'utilise la classe EntityManager
        // je demande à Symfony de l'instancier pour moi (grâce au système
        // d'autowire)
        // cette classe me permet de persister mon entité (de préparer sa sauvegarde
        // en bdd), puis d'effectuer l'enregistrement (génère et éxecute une requête SQL)
        $entityManager->persist($book);
        $entityManager->flush();

        return $this->render('book_create.html.twig');
    }

    /**
     * @Route("/book/update/{id}", name="book_update")
     */
    public function updateBook($id, BookRepository $bookRepository, EntityManagerInterface $entityManager)
    {
       // aller un chercher un livre (doctrine va me donner un objet, une instance de la classe Book)
        $book = $bookRepository->find($id);

        // modifier les valeurs via les setters
        $book->setTitle('Mad Max reloaded');

        // enregistrer en bdd avec l'entity manager
        $entityManager->persist($book);
        $entityManager->flush();

        return $this->render('book_update.html.twig');
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
