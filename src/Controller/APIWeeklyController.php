<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\WorkTime;
use App\Repository\AtelierRepository;
use App\Repository\WorkTimeRepository;
use Doctrine\ORM\NonUniqueResultException;
use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class APIWeeklyController
 * @package App\Controller
 * @IsGranted("ROLE_PRO_AGENDA")
 * @IsGranted("ROLE_PRO_AGENDA")
 */
class APIWeeklyController extends AbstractController
{


    /**
     * @Route("/api/weekly/add", name="api_weekly_add", methods={"PUT"})
     * @param Request $request
     * @return Response
     * @throws Exception
     */
    public function add(Request $request)
    {
        $datas = json_decode($request->getContent());
        $workTime = new WorkTime();
        $workTime->setCalendarId((int) $datas->id)->setPro($this->getUser()->getPro())->setDay($datas->day)->setStart(new \DateTime($datas->start))->setEnd(new \DateTime($datas->end));
        $em = $this->getDoctrine()->getManager();
        $em->persist($workTime);
        $em->flush();
        return new Response('OK', 200);
    }

    /**
     * @Route("/api/weekly/rm", name="api_weekly_rm", methods={"PUT"})
     * @param Request $request
     * @return Response
     * @throws NonUniqueResultException
     */
    public function rm(Request $request)
    {
        $datas = json_decode($request->getContent());
        $id = (int) $datas->id;
        if ($id == 0)
            $id = (int) $datas->defId;
        $em = $this->getDoctrine()->getManager();
        $workTime = $em->getRepository(WorkTime::class)->findByCalendarId($id);
        if (!$workTime){
            return new Response('not found for '.$id, 404);
        }
        $em->remove($workTime);
        $em->flush();

        return new Response('OK', 200);
    }

    /**
     * @Route("/api/weekly/edit", name="api_weekly_edit", methods={"PUT"})
     * @param Request $request
     * @return Response
     * @throws Exception
     */
    public function edit(Request $request)
    {
        $datas = json_decode($request->getContent());

        $id = (int) $datas->id;
        if ($id == 0)
            $id = (int) $datas->defId;
        $end = new \DateTime($datas->end);
        $start = new \DateTime($datas->start);
        $em = $this->getDoctrine()->getManager();
        $workTime = $em->getRepository(WorkTime::class)->findByCalendarId($id);
        if (!$workTime){
            return new Response('Not found for calendar id '.$id, 404);
        }
        $workTime->setStart($start);
        $workTime->setEnd($end);
        $workTime->setDay($datas->day);
        $em->flush();
        return new Response('OK', 202);
    }
}
