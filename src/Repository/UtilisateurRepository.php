<?php

namespace App\Repository;

use App\Entity\Utilisateur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Utilisateur>
 *
 * @method Utilisateur|null find($id, $lockMode = null, $lockVersion = null)
 * @method Utilisateur|null findOneBy(array $criteria, array $orderBy = null)
 * @method Utilisateur[]    findAll()
 * @method Utilisateur[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UtilisateurRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Utilisateur::class);
    }

    
    public function countUsers()
{
    return $this->createQueryBuilder('u')
        ->select('COUNT(u.id) as userCount')
        ->getQuery()
        ->getSingleScalarResult();
}

public function countCoaches()
{
    return $this->createQueryBuilder('u')
        ->select('COUNT(u.id)')
        ->leftJoin('u.role', 'r')
        ->where('r.nom_role = :coachRole')
        ->setParameter('coachRole', 'ROLE_COACH')
        ->getQuery()
        ->getSingleScalarResult();
}

public function countPatients()
{
    return $this->createQueryBuilder('u')
        ->select('COUNT(u.id)')
        ->leftJoin('u.role', 'r')
        ->where('r.nom_role = :patientRole')
        ->setParameter('patientRole', 'ROLE_PATIENT')
        ->getQuery()
        ->getSingleScalarResult();
}

public function countUsersByCity()
{
    return $this->createQueryBuilder('u')
        ->select('COUNT(u.id) as userCount, u.ville')
        ->groupBy('u.ville')
        ->getQuery()
        ->getResult();
}

public function countUsersByGenre($genre)
{
    $totalUsers = $this->createQueryBuilder('u')
        ->select('COUNT(u.id)')
        ->getQuery()
        ->getSingleScalarResult();

    $genreCount = $this->createQueryBuilder('u')
        ->select('COUNT(u.id)')
        ->where('u.genre = :genre')
        ->setParameter('genre', $genre)
        ->getQuery()
        ->getSingleScalarResult();

    // Calculer le pourcentage
    $percentage = ($genreCount / $totalUsers) * 100;

    return round($percentage, 2); // Arrondi à 2 décimales
}

public function findByNom($searchTerm)
{
    $searchTerm = '%' . $searchTerm . '%'; // Assurez-vous que les caractères de pourcentage sont ajoutés

    $query = $this->createQueryBuilder('u')
        ->where('u.nom LIKE :searchTerm')
        ->setParameter('searchTerm', $searchTerm)
        ->getQuery();

    return $query->getResult();
}
//    /**
//     * @return Utilisateur[] Returns an array of Utilisateur objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('u.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Utilisateur
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
