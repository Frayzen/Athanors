<?php

namespace App\Repository;

use App\Entity\ExtraPage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ExtraPage|null find($id, $lockMode = null, $lockVersion = null)
 * @method ExtraPage|null findOneBy(array $criteria, array $orderBy = null)
 * @method ExtraPage[]    findAll()
 * @method ExtraPage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ExtraPageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ExtraPage::class);
    }

    // /**
    //  * @return ExtraPage[] Returns an array of ExtraPage objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ExtraPage
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
