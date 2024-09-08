<?php

namespace App\Controller;

use App\Entity\Overtime;
use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class APIOverTimeController
 * @package App\Controller
 * @IsGranted("ROLE_PRO_AGENDA")
 */
class APIOverTimeController extends AbstractController
{

    /**
     * @Route("/api/overtime/add", name="api_overtime_add", methods={"PUT"})
     * @param Request $request
     * @return Response
     * @throws Exception
     */
    public function add(Request $request)
    {
        $datas = json_decode($request->getContent());
        $overtime = new Overtime();
        $overtime->setCalendarId((int) $datas->id)->setPro($this->getUser()->getPro())->setStart(new \DateTime($datas->start))->setEnd(new \DateTime($datas->end));
        $em = $this->getDoctrine()->getManager();
        $em->persist($overtime);
        $em->flush();
        return new Response('OK', 200);
    }

    /**
     * @Route("/api/overtime/rm", name="api_overtime_rm", methods={"PUT"})
     * @param Request $request
     * @return Response
     */
    public function rm(Request $request)
    {
        $datas = json_decode($request->getContent());
        $id = (int) $datas->id;
        if ($id == 0)
            $id = (int) $datas->defId;
        $em = $this->getDoctrine()->getManager();
        $overtime = $em->getRepository(Overtime::class)->findByCalendarId($id);
        if (!$overtime){
            return new Response('not found for '.$id, 404);
        }
        $em->remove($overtime);
        $em->flush();

        return new Response('OK', 200);
    }

    /**
     * @Route("/api/overtime/edit", name="api_overtime_edit", methods={"PUT"})
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
        $overtime = $em->getRepository(Overtime::class)->findByCalendarId($id);
        if (!$overtime){
            return new Response('Not found for calendar id '.$id, 404);
        }
        $overtime->setStart($start);
        $overtime->setEnd($end);
        $em->flush();
        return new Response('OK', 202);
    }
}
