<?php

namespace App\Repository;

use App\Entity\CalendarException;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CalendarException|null find($id, $lockMode = null, $lockVersion = null)
 * @method CalendarException|null findOneBy(array $criteria, array $orderBy = null)
 * @method CalendarException[]    findAll()
 * @method CalendarException[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CalendarExceptionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CalendarException::class);
    }

    // /**
    //  * @return CalendarException[] Returns an array of CalendarException objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CalendarException
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    public function findAllForAdmin($admin)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.pro = :val')
            ->andWhere('c.end >= :today')
            ->setParameter('val', $admin)
            ->setParameter('today', new \DateTime())
            ->getQuery()
            ->getResult();
    }
}
