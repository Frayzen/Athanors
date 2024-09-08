<?php

namespace App\Controller;

use App\Entity\Atelier;
use App\Entity\Overtime;
use App\Repository\SupervisorRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class SupervisorController
 * @package App\Controller
 * @IsGranted ("ROLE_PRO_SUPERVISOR")
 */
class APISupervisorController extends AbstractController
{
    /**
     * @Route("/api/supervisor/{id}/add", name="api_supervisor_add", methods={"PUT"})
     * @param Atelier $atelier
     * @param Request $request
     * @param SupervisorRepository $repository
     * @return Response
     */
    public function add(Atelier  $atelier, Request $request, SupervisorRepository $repository)
    {
        $datas = json_decode($request->getContent());
        $supervisor = $repository->find($datas->supervisorId);
        if ($atelier->getSupervisors()->contains($supervisor))
            return new Response('No content', 204);
        $atelier->addSupervisor($supervisor);
        $em = $this->getDoctrine()->getManager();
        $em->persist($atelier);
        $em->flush();
        return new Response('OK', 200);
    }

    /**
     * @Route("/api/supervisor/{id}/remove", name="api_supervisor_remove", methods={"PUT"})
     * @param Atelier $atelier
     * @param Request $request
     * @param SupervisorRepository $repository
     * @return Response
     */
    public function remove(Atelier  $atelier, Request $request, SupervisorRepository $repository)
    {
        $datas = json_decode($request->getContent());
        $supervisor = $repository->find($datas->supervisorId);
        if (!$atelier->getSupervisors()->contains($supervisor))
            return new Response('No content', 204);
        $atelier->removeSupervisor($supervisor);
        $em = $this->getDoctrine()->getManager();
        $em->persist($atelier);
        $em->flush();
        return new Response('OK', 200);
    }
}
