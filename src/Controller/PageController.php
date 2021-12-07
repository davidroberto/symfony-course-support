<?php


namespace App\Controller;

use App\Repository\AuthorRepository;
use App\Repository\BookRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class PageController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function home(BookRepository $bookRepository, AuthorRepository $authorRepository)
    {
        $lastBooks = $bookRepository->findBy([], ['id' => 'DESC'], 3);
        $lastAuthors = $authorRepository->findBy([], ['id' => 'DESC'], 3);

        return $this->render("home.html.twig", [
            'lastBooks'=> $lastBooks,
            'lastAuthors' => $lastAuthors
        ]);
    }

}
