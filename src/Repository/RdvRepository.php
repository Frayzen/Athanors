<?php

namespace App\Repository;

use App\Entity\Professional;
use App\Entity\Rdv;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Rdv|null find($id, $lockMode = null, $lockVersion = null)
 * @method Rdv|null findOneBy(array $criteria, array $orderBy = null)
 * @method Rdv[]    findAll()
 * @method Rdv[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RdvRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Rdv::class);
    }

    // /**
    //  * @return Rdv[] Returns an array of Rdv objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Rdv
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    public function getJSONedDatas(User $admin)
    {
        $datas = $this->findBy(['pro'=>$admin->getPro(), 'validate'=>true]);
        $ret = [];
        foreach ($datas as $data) {
            $ret[] = [
                'id'=>$data->getId(),
                'start'=>$data->getStart()->format("Y-m-d H:i"),
                'end'=>$data->getEnd()->format("Y-m-d H:i"),
                'title'=>$data->getClient()->getFullName(),
                'backgroundColor'=>'gray'
            ];
        }
        return json_encode($ret);
    }

    public function findAllAfterNow()
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.start > :now')
            ->setParameter("now", new \DateTime())
            ->orderBy('r.start', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }
}
