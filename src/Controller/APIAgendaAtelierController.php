<?php

namespace App\Controller;

use App\Entity\Atelier;
use App\Entity\AtelierSession;
use App\Entity\Overtime;
use App\Entity\WorkTime;
use App\Repository\AtelierSessionRepository;
use Doctrine\ORM\NonUniqueResultException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @IsGranted ("ROLE_PRO_ATELIER")
 * Class APIAgendaAtelierController
 * @package App\Controller
 */
class APIAgendaAtelierController extends AbstractController
{
    /**
     * @Route("/api/atelier/{id}/agenda/add", name="api_atelier_agenda_add", methods={"PUT"})
     * @param Atelier $atelier
     * @param Request $request
     * @return Response
     * @throws \Exception
     */
    public function add(Atelier $atelier, Request $request)
    {
        $datas = json_decode($request->getContent());
        $session = new AtelierSession();
        $session->setCalendarId((int) $datas->id)->setAtelier($atelier)->setStart(new \DateTime($datas->start))->setEnd(new \DateTime($datas->end));
        $em = $this->getDoctrine()->getManager();
        $em->persist($session);
        $em->flush();
        return new Response('OK', 200);
    }

    /**
     * @Route("/api/atelier/{id}/agenda/rm", name="api_atelier_agenda_rm", methods={"PUT"})
     * @param Atelier $atelier
     * @param Request $request
     * @param AtelierSessionRepository $atelierSessionRepository
     * @return Response
     * @throws NonUniqueResultException
     */
    public function rm(Atelier $atelier, Request $request, AtelierSessionRepository $atelierSessionRepository)
    {
        $datas = json_decode($request->getContent());
        $id = (int) $datas->id;
        if ($id == 0)
            $id = (int) $datas->defId;
        $em = $this->getDoctrine()->getManager();
        $session = $atelierSessionRepository->findByCalendarId($id);
        if (!$session){
            return new Response('not found for '.$id, 404);
        }
        foreach ($session->getAnswerUserSessions() as $answer)
            $em->remove($answer);
        $em->remove($session);
        $em->flush();

        return new Response('OK', 200);
    }

    /**
     * @Route("/api/atelier/{id}/agenda/edit", name="api_atelier_agenda_edit", methods={"PUT"})
     * @param Atelier $atelier
     * @param Request $request
     * @return Response
     * @throws \Exception
     */
    public function edit(Atelier $atelier, Request $request, AtelierSessionRepository $atelierSessionRepository)
    {
        $datas = json_decode($request->getContent());

        $id = (int) $datas->id;
        if ($id == 0)
            $id = (int) $datas->defId;
        $end = new \DateTime($datas->end);
        $start = new \DateTime($datas->start);
        $em = $this->getDoctrine()->getManager();
        $session = $atelierSessionRepository->findByCalendarId($id);
        if (!$session){
            return new Response('Not found for calendar id '.$id, 404);
        }
        $session->setStart($start);
        $session->setEnd($end);
        $em->flush();
        return new Response('OK', 202);
    }

    /**
     * @Route("/api/atelier/{id}/agenda/session/edit", name="api_atelier_session_edit", methods={"PUT"})
     * @param Atelier $atelier
     * @param AtelierSessionRepository $atelierSessionRepository
     * @param Request $request
     * @return Response
     */
    public function edit_session(Atelier $atelier, AtelierSessionRepository $atelierSessionRepository, Request $request)
    {
        $datas = json_decode($request->getContent());
        $id = $datas->id;
        $name = $datas->name;
        $theme = $datas->theme;
        $session = $atelierSessionRepository->findOneBy(['calendarId'=>$id, 'atelier'=>$atelier]);
        if(!$atelier->getSessionsMandatory()) {
            $delay = $datas->maxDayAnswer;
            $session->setDelayAnswer(new \DateTime($delay));
        }else{
            $session->setDelayAnswer(new \DateTime());
        }
        $session->setThemes($theme);
        $session->setName($name);
        $this->getDoctrine()->getManager()->flush();
        return new Response('OK', 202);
    }
}
