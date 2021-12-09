<?php

namespace App\Controller;

use App\Entity\Book;
use App\Form\BookType;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AdminBookController extends AbstractController
{
    /**
     * @Route("/admin/books", name="admin_books")
     */
    public function books(BookRepository $bookRepository)
    {
        $books = $bookRepository->findAll();

        return $this->render("admin/books.html.twig",['books'=> $books]);
    }



    /**
     * @Route("/admin/book/delete/{id}", name="admin_book_delete")
     */
    public function bookDelete($id, BookRepository $bookRepository, EntityManagerInterface $entityManager)
    {
        $book = $bookRepository->find($id);

        $entityManager->remove($book);
        $entityManager->flush();

        return $this->redirectToRoute("books");
    }



    ////////////////////////////////////////////
    /// ////////////////////////////////////////////
    /// ////////////////////////////////////////////
    /// ////////////////////////////////////////////


    /**
     * @Route("/admin/book/create", name="admin_book_create")
     */
    public function createBook()
    {
        // je veux créer un nouvel enregistrement dans la table book
        // donc je créé une instance de l'entité Book
        $book = new Book();

        // j'utilise la méthode createForm (d'AbstractController) qui va me permettre de créer un
        // formulaire en utilisant le gabarit généré (BookType) en lignes de commandes
        // et je lui associe l'instance de l'entité Book
        $bookForm = $this->createForm(BookType::class, $book);


        // j'envoie à mon twig la variable contenant le formulaire
        // préparé pour l'affichage (avec la méthode createView())
        return $this->render("admin/book_create.html.twig", [
            'bookForm' => $bookForm->createView()
        ]);
    }

    /**
     * @Route("/admin/book/update/{id}", name="admin_book_update")
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

        return $this->render("admin/book_update.html.twig");
    }

    /**
     * @Route("/admin/book/{id}", name="admin_book")
     */
    public function book($id, BookRepository $bookRepository)
    {
        $book = $bookRepository->find($id);

        return $this->render("admin/book.html.twig",['book'=> $book]);
    }

}
