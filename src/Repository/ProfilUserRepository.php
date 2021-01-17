<?php

namespace App\Repository;

use App\Entity\ProfilUser;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ProfilUser|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProfilUser|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProfilUser[]    findAll()
 * @method ProfilUser[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProfilUserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProfilUser::class);
    }

    // /**
    //  * @return ProfilUser[] Returns an array of ProfilUser objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ProfilUser
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
