<?php

namespace App\Repository;

use App\Entity\Typegroupe;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Typegroupe>
 *
 * @method Typegroupe|null find($id, $lockMode = null, $lockVersion = null)
 * @method Typegroupe|null findOneBy(array $criteria, array $orderBy = null)
 * @method Typegroupe[]    findAll()
 * @method Typegroupe[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TypegroupeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Typegroupe::class);
    }
    public function trie()
    {
        return $this->createQueryBuilder('g')
            ->orderBy('g.id', 'DESC') 
            ->getQuery()
            ->getResult();
    }
    public function recherche($x){
       return $this->createQueryBuilder('c')
           ->where('c.nomtype LIKE :nomtype')
           ->setParameter('nomtype', '%'.$x.'%')
           ->getQuery()
           ->getResult();
   }
//    /**
//     * @return Typegroupe[] Returns an array of Typegroupe objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('t.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Typegroupe
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
