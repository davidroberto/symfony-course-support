<?php

namespace App\Controller;

use App\Entity\Book;
use App\Form\BookType;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
    //Récupére la classe Request car elle va contenir les données POST du form
    public function createBook(Request $request, EntityManagerInterface $entityManager)
    {
        // je veux créer un nouvel enregistrement dans la table book
        // donc je créé une instance de l'entité Book
        $book = new Book();

        // j'utilise la méthode createForm (d'AbstractController) qui va me permettre de créer un
        // formulaire en utilisant le gabarit généré (BookType) en lignes de commandes
        // et je lui associe l'instance de l'entité Book
        $bookForm = $this->createForm(BookType::class, $book);

        // Asssocier le formulaire à la classe Request (le formulaire
        // lui est associé à l'instance de l'entité Book)
        $bookForm->handleRequest($request);

        // Vérifier que le formulaire a été envoyé
        // le isValid empeche que des données invalides par rapports aux types de colonnes
        // soient insérées + prévient les injections SQL
        if ($bookForm->isSubmitted() && $bookForm->isValid()) {
            // On enregistre l'entité en bdd avec l'entité manager (vu que l'instance de l'entité est reliée
            // au form et que le formulaire est reliée à la classe Request), Symfony va
            // automatiquement mettre les données du form dans l'instance de l'entité
            $entityManager->persist($book);
            $entityManager->flush();
        }

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
