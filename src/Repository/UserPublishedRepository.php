<?php

namespace App\Repository;

use App\Entity\UserPublished;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method UserPublished|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserPublished|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserPublished[]    findAll()
 * @method UserPublished[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserPublishedRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserPublished::class);
    }

    // /**
    //  * @return UserPublished[] Returns an array of UserPublished objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?UserPublished
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
