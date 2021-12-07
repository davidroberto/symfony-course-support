<?php


namespace App\Controller;

use App\Repository\AuthorRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AuthorController extends AbstractController
{

    /**
     * @Route("/authors", name="authors")
     */
    public function authors(AuthorRepository $authorRepository)
    {
        $authors = $authorRepository->findAll();
        return $this->render('authors.html.twig', [
            'authors' => $authors
        ]);
    }

    /**
     * @Route("/author/{id}", name="author")
     */
    public function author($id, AuthorRepository $authorRepository)
    {
        $author = $authorRepository->find($id);
        return $this->render('author.html.twig', [
            'author' => $author
        ]);
    }

}
