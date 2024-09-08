<?php

namespace App\Repository;

use App\Entity\CreneauAtelier;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CreneauAtelier|null find($id, $lockMode = null, $lockVersion = null)
 * @method CreneauAtelier|null findOneBy(array $criteria, array $orderBy = null)
 * @method CreneauAtelier[]    findAll()
 * @method CreneauAtelier[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CreneauAtelierRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CreneauAtelier::class);
    }

    // /**
    //  * @return CreneauAtelier[] Returns an array of CreneauAtelier objects
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
    public function findOneBySomeField($value): ?CreneauAtelier
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
