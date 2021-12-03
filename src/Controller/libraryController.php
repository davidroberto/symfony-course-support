<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


//Je crée un Controller. Celui-ci va permettre de centraliser toutes les Request
//de l'URL de mon navigateur et de retourner des Response sous forme d'un affichage
//à l'écran.
//J'appelle la classe AbstractController qui va permettre d'appeller un template,
//d'afficher du contenu sur mon navigateur.
class libraryController extends AbstractController
{
    // j'initialise une propriété privé $books
    // qui contiendra la liste des livres afin d'éviter de
    // répéter le code

    private $books;
    // je créé une méthode constructor, ce qui va permettre
    // d'afficher les pages créées dans le controleur
    public function __construct()
    {
        $this->books = [
            1 => [
                "title" => "Dune",
                "author" => "Franck Herbert",
                "image" => "https://images-na.ssl-images-amazon.com/images/I/81fuj9MOHfL.jpg",
                "publishedAt" => new \DateTime('NOW'),
                "id" => 1
            ],
            2 => [
                "title" => "Silo",
                "author" => "Hugh Howey",
                "image" => "https://m.media-amazon.com/images/I/51GzKI0JjkL.jpg",
                "publishedAt" => new \DateTime('NOW'),
                "id" => 2
            ],
            3 => [
                "title" => "Win",
                "author" => "Harlan Coben",
                "image" =>"https://images-na.ssl-images-amazon.com/images/I/81MH9QEw+5L.jpg",
                "publishedAt" => new \DateTime('NOW'),
                "id" => 3
            ],
            4 => [
                "title" => "La part de l'autre",
                "author" => "Éric-Emmanuel Schmitt",
                "image" =>"https://static.fnac-static.com/multimedia/FR/Images_Produits/FR/fnac.com/Visual_Principal_340/9/7/3/9782253155379/tsp20121001175117/La-part-de-l-autre.jpg",
                "publishedAt" => new \DateTime('NOW'),
                "id" => 4
            ],
            5 => [
                "title" => "Snowman",
                "author" => "Jo Nesbo",
                "image" => "https://images-na.ssl-images-amazon.com/images/I/817jnkcA+jL.jpg",
                "publishedAt" => new \DateTime('NOW'),
                "id" => 5
            ]
        ];

    }
    //Après avoir initialisé la propriété privée, je supprime
    //mon ancien tableau de ma fonction home et book,
    //qui maintenant n'ont plus lieu d'être.
    /**
     * @Route ("/", name="home")
     */
    public function home()
    {
        $array = array_slice($this->books, -3);

        // j'appelle la fonction render qui va envoyer les informations demandés sur un fichier
        // HTML, "home.html.twig"
        return $this->render("home.html.twig", ['home'=> $array]);
    }

    /**
     * @Route ("/book/{id}", name="book")
     */
    // je crée une nouvelle route et une nouvelle fonction qui va me permettre de récupérer les données de la BDD
    // et de les stocker dans une nouvelle vraiable $book. Combiné à la fonction, cela va me permettre d'afficher chaque livre
    // un par un dans mon navigateur.
    public function book($id)
    {
        // j'appelle la fonction render qui va envoyer les informations demandés sur un fichier
        // HTML, "book.html.twig"
        return $this->render("book.html.twig",['book'=> $this->books[$id]]);

    }

    /**
     * @Route ("/books", name="books")
     */
    // je crée une nouvelle route et une nouvelle fonction qui va me permettre de récupérer les données de la BDD
    // et de les stocker dans une nouvelle vraiable $book. Combiné à la fonction, cela va me permettre d'afficher chaque livre
    // un par un dans mon navigateur.
    public function books()
    {
        // j'appelle la fonction render qui va envoyer les informations demandés sur un fichier
        // HTML, "book.html.twig"
        return $this->render("books.html.twig",['books'=> $this->books]);

    }


}
