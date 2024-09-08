<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\WorkTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method WorkTime|null find($id, $lockMode = null, $lockVersion = null)
 * @method WorkTime|null findOneBy(array $criteria, array $orderBy = null)
 * @method WorkTime[]    findAll()
 * @method WorkTime[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WorkTimeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WorkTime::class);
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

    /*
    public function findOneBySomeField($value): ?WorkTime
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
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

    public function getJSONedDatas(User $user)
    {
        return json_encode($this->getData($user));
    }
    public function getData(User $user){

        $results = $this->findByAdminUser($user);
        $rdvs = [];
        foreach ($results as $result){
            $rdvs[] = [
                'id'=>$result->getCalendarId(),
                'daysOfWeek'=>[$result->getDay()],
                'startTime'=>$result->getStart()->format("H:i"),
                'endTime'=>$result->getEnd()->format("H:i") ,
                'title'=>''
            ];
        }
        return $rdvs;
    }
}
