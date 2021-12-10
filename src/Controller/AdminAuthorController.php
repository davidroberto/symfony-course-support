<?php


namespace App\Controller;

use App\Entity\Author;
use App\Form\AuthorType;
use App\Repository\AuthorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AdminAuthorController extends AbstractController
{

    /**
     * @Route("/admin/authors", name="admin_authors")
     */
    public function authors(AuthorRepository $authorRepository)
    {
        $authors = $authorRepository->findAll();
        return $this->render('admin/authors.html.twig', [
            'authors' => $authors
        ]);
    }

    /**
     * @Route("/admin/author/create", name="admin_author_create")
     */
    public function createAuthor(Request $request, EntityManagerInterface $entityManager)
    {
        $author = new Author();
        $authorForm = $this->createForm(AuthorType::class, $author);
        $authorForm->handleRequest($request);

        if ($authorForm->isSubmitted() && $authorForm->isValid()) {
            $entityManager->persist($author);
            $entityManager->flush();
        }

        return $this->render('admin/author_create.html.twig', [
            'authorForm' => $authorForm->createView()
        ]);
    }

    /**
     * @Route("/admin/author/update/{id}", name="admin_author_update")
     */
    public function updateAuthor($id, Request $request, EntityManagerInterface $entityManager, AuthorRepository $authorRepository)
    {
        $author = $authorRepository->find($id);

        $authorForm = $this->createForm(AuthorType::class, $author);
        $authorForm->handleRequest($request);

        if ($authorForm->isSubmitted() && $authorForm->isValid()) {
            $entityManager->persist($author);
            $entityManager->flush();
        }

        return $this->render('admin/author_update.html.twig', [
            'authorForm' => $authorForm->createView()
        ]);
    }

    /**
     * @Route("/admin/author/{id}", name="admin_author")
     */
    public function author($id, AuthorRepository $authorRepository)
    {
        $author = $authorRepository->find($id);
        return $this->render('admin/author.html.twig', [
            'author' => $author
        ]);
    }

    /**
     * @Route("/admin/author/delete/{id}", name="admin_author_delete" )
     */
    public function deleteAuthor($id, EntityManagerInterface $entityManager, AuthorRepository $authorRepository)
    {
        $author = $authorRepository->find($id);

        $entityManager->remove($author);
        $entityManager->flush();

        return $this->redirectToRoute('admin_authors');
    }



}
