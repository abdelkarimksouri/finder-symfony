<?php

namespace App\Repository;

use App\Entity\TestClass;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TestClass|null find($id, $lockMode = null, $lockVersion = null)
 * @method TestClass|null findOneBy(array $criteria, array $orderBy = null)
 * @method TestClass[]    findAll()
 * @method TestClass[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TestClassRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TestClass::class);
    }

    // /**
    //  * @return TestClass[] Returns an array of TestClass objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TestClass
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
