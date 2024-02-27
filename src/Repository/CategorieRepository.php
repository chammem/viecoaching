<?php

namespace App\Repository;

use App\Entity\Categorie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Categorie>
 *
 * @method Categorie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Categorie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Categorie[]    findAll()
 * @method Categorie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategorieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Categorie::class);
    }

    //rech cat  par son nom
    public function recherche($nom){
        return $this->createQueryBuilder('c')
            ->where('c.nomCategorie LIKE :nomCategorie')
            ->setParameter('nomCategorie', '%'.$nom.'%')
            ->getQuery()
            ->getResult();
    }
//resbycat
public function findAllWithRessources()
    {
        return $this->createQueryBuilder('c')
            ->leftJoin('c.ressource', 'r')
            ->addSelect('r')
            ->getQuery()
            ->getResult();
    }    
    //trie
    public function trie()
    {
        return $this->createQueryBuilder('c')
            ->orderBy('c.id', 'DESC') 
            ->getQuery()
            ->getResult();
    }
//    /**
//     * @return Categorie[] Returns an array of Categorie objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Categorie
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
