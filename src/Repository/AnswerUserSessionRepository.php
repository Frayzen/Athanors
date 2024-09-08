<?php

namespace App\Repository;

use App\Entity\AnswerUserSession;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method AnswerUserSession|null find($id, $lockMode = null, $lockVersion = null)
 * @method AnswerUserSession|null findOneBy(array $criteria, array $orderBy = null)
 * @method AnswerUserSession[]    findAll()
 * @method AnswerUserSession[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AnswerUserSessionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AnswerUserSession::class);
    }

    // /**
    //  * @return AnswerUserSession[] Returns an array of AnswerUserSession objects
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
    public function findOneBySomeField($value): ?AnswerUserSession
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
