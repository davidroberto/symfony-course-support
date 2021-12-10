<?php


namespace App\Controller;

use App\Repository\AuthorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
