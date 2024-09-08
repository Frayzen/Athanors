<?php

namespace App\Repository;

use App\Entity\Overtime;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Overtime|null find($id, $lockMode = null, $lockVersion = null)
 * @method Overtime|null findOneBy(array $criteria, array $orderBy = null)
 * @method Overtime[]    findAll()
 * @method Overtime[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OvertimeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Overtime::class);
    }

    // /**
    //  * @return Overtime[] Returns an array of Overtime objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('o.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Overtime
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */public function getJSONedDatas(User $user)
    {
        return json_encode($this->getData($user));
    }
    public function getData(User $user){

        $results = $this->findByAdminUser($user);
        $rdvs = [];
        foreach ($results as $result){
            $rdvs[] = [
                'id'=>$result->getCalendarId(),
                'start'=>$result->getStart()->format("Y-m-d H:i"),
                'end'=>$result->getEnd()->format("Y-m-d H:i") ,
                'title'=>''
            ];
        }
        return $rdvs;
    }

    public function findByCalendarId(int $id)
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.calendar_id = :val')
            ->setParameter('val', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @param $user User
     * @return int|mixed|string
     */
    public function findByAdminUser(User $user)
    {
        if ($user->getPro() == null)
            return [];
        return $this->createQueryBuilder('w')
            ->andWhere('w.pro = :val')
            ->setParameter('val', $user->getPro())
            ->orderBy('w.start', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }

}
