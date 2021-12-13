<?php

namespace App\Repository;

use App\Entity\Book;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;


class BookRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Book::class);
    }


    public function searchByTitle($word)
    {
        // j'utilise la méthode createQueryBuilder
        // provenant de la classe parent
        // et je définis un alias pour la table book
        $queryBuilder = $this->createQueryBuilder('book');

        // je demande à Doctrine de créer une requête SQL
        // qui fait une requête SELECT sur la table book
        // à condition que le titre du book
        // contiennent le contenu de $word (à un endroit ou à un autre, grâce à LIKE %xxxx%)
        $query = $queryBuilder->select('book')
            ->where('book.title LIKE :word')
            ->setParameter('word', '%'.$word.'%')
            ->getQuery();

        // je récupère les résultats de la requête SQL
        // et je les retourne
        return $query->getResult();
    }
}
