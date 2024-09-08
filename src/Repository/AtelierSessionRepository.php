<?php

namespace App\Repository;

use App\Entity\Atelier;
use App\Entity\AtelierSession;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method AtelierSession|null find($id, $lockMode = null, $lockVersion = null)
 * @method AtelierSession|null findOneBy(array $criteria, array $orderBy = null)
 * @method AtelierSession[]    findAll()
 * @method AtelierSession[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AtelierSessionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AtelierSession::class);
    }

    // /**
    //  * @return AtelierSession[] Returns an array of AtelierSession objects
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
    public function findOneBySomeField($value): ?AtelierSession
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    /**
     * @param Atelier $atelier
     * @return int|mixed|string
     */
    public function findByAtelier(Atelier $atelier)
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.atelier = :val')
            ->setParameter('val', $atelier)
            ->orderBy('w.start', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }
    /**
     * @param int $id
     * @return int|mixed|string|null
     * @throws NonUniqueResultException
     */
    public function findByCalendarId(int $id)
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.calendarId = :val')
            ->setParameter('val', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function getJSONedDatas(Atelier $atelier)
    {
        return json_encode($this->getData($atelier));
    }
    public function getData(Atelier $atelier){

        $results = $this->findByAtelier($atelier);
        $sessions = [];
        foreach ($results as $result){
            $sessions[] = [
                'id'=>$result->getCalendarId(),
                'start'=>$result->getStart()->format("Y-m-d H:i"),
                'end'=>$result->getEnd()->format("Y-m-d H:i") ,
                'title'=>$result->getName() == null ? "" : $result->getName()
            ];
        }
        return $sessions;
    }
}
