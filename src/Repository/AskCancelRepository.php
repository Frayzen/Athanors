<?php

namespace App\Repository;

use App\Entity\AskCancel;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method AskCancel|null find($id, $lockMode = null, $lockVersion = null)
 * @method AskCancel|null findOneBy(array $criteria, array $orderBy = null)
 * @method AskCancel[]    findAll()
 * @method AskCancel[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AskCancelRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AskCancel::class);
    }

    // /**
    //  * @return AskCancel[] Returns an array of AskCancel objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?AskCancel
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
