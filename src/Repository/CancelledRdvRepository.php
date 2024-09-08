<?php

namespace App\Repository;

use App\Entity\CancelledRdv;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CancelledRdv|null find($id, $lockMode = null, $lockVersion = null)
 * @method CancelledRdv|null findOneBy(array $criteria, array $orderBy = null)
 * @method CancelledRdv[]    findAll()
 * @method CancelledRdv[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CancelledRdvRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CancelledRdv::class);
    }

    // /**
    //  * @return CancelledRdv[] Returns an array of CancelledRdv objects
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
    public function findOneBySomeField($value): ?CancelledRdv
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    public function findElemsViewed(User $user, bool $viewed)
    {
        $r = $this->createQueryBuilder('c')
            ->andWhere('c.user = :user')
            ->andWhere('c.viewed = :viewed');
        if ($viewed){
            $r->andWhere('c.start >= :now')
            ->setParameter('now', new \DateTime());
        }
        return $r->setParameter('user', $user)
            ->setParameter('viewed', $viewed)
            ->orderBy('c.start', 'ASC')
            ->getQuery()
            ->getResult();

    }
}
