<?php

namespace App\Controller;

use App\Entity\Book;
use App\Form\BookType;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

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


    /**
     * @Route("/admin/book/create", name="admin_book_create")
     */
    public function createBook(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger)
    {
        $book = new Book();
        $bookForm = $this->createForm(BookType::class, $book);
        $bookForm->handleRequest($request);

        if ($bookForm->isSubmitted() && $bookForm->isValid()) {
            // gestion de l'upload d'image
            // 1) récupérer le fichier uploadé
            $coverFile = $bookForm->get('coverFilename')->getData();

            if ($coverFile) {
                // 2) récupérer le nom du fichier uploadé
                $originalFilename = pathinfo($coverFile->getClientOriginalName(), PATHINFO_FILENAME);

                // 3) renommer le fichier avec un nom unique
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$coverFile->guessExtension();

                // 4) déplacer le fichier dans le dossier publique
                $coverFile->move(
                    $this->getParameter('cover_directory'),
                    $newFilename
                );

                // 5) enregistrer le nom du fichier dans la colonne coverFilename
                $book->setCoverFilename($newFilename);
            }


            $entityManager->persist($book);
            $entityManager->flush();

            $this->addFlash('success', "Le livre a bien été enregistré !");
            return $this->redirectToRoute('admin_books');
        }

        return $this->render("admin/book_create.html.twig", [
            'bookForm' => $bookForm->createView()
        ]);
    }

    /**
     * @Route("/admin/book/update/{id}", name="admin_book_update")
     */
    public function updateBook($id, Request $request, BookRepository $bookRepository, EntityManagerInterface $entityManager)
    {
        // je récupère un livre en bdd pour le mettre à jour
        $book = $bookRepository->find($id);

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
        return $this->render("admin/book_update.html.twig", [
            'bookForm' => $bookForm->createView()
        ]);
    }

    /**
     * @Route("/admin/book/{id}", name="admin_book")
     */
    public function book($id, BookRepository $bookRepository)
    {
        $book = $bookRepository->find($id);

        return $this->render("admin/book.html.twig",['book'=> $book]);
    }


    /**
     * @Route("/admin/search", name="admin_search_books")
     */
    public function searchBooks(BookRepository $bookRepository, Request $request)
    {

        // je récupère ce que tu l'utilisateur a recherché grâce à la classe Request
        $word = $request->query->get('q');

        // je fais ma requête en BDD grâce à la méthode que j'ai créée searchByTitle
        $books = $bookRepository->searchByTitle($word);

        return $this->render('admin/books_search.html.twig', [
            'books' => $books
        ]);
    }

}
