<?php

namespace App\Controller;

use App\Entity\Atelier;
use App\Entity\Supervisor;
use App\Form\AtelierType;
use App\Form\SupervisorType;
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
class SupervisorController extends AbstractController
{
    /**
     * @Route("/panel/supervisors", name="panel_supervisor_list")
     * @param SupervisorRepository $supervisorRepository
     * @return Response
     */
    public function index(SupervisorRepository $supervisorRepository): Response
    {
        return $this->render('supervisor/list_supervisor.html.twig', [
            'supervisors' => $supervisorRepository->findAll(),
        ]);
    }

    /**
     * @Route("/panel/supervisor/create", name="panel_supervisor_create")
     * @param Request $request
     * @return Response
     */
    public function create(Request $request): Response
    {
        $supervisor = new Supervisor();

        $form = $this->createForm(SupervisorType::class, $supervisor);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $this->getDoctrine()->getManager()->persist($supervisor);
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute("panel_supervisor_list");
        }
        return $this->render('supervisor/create_supervisor.html.twig', [
            'form'=>$form->createView()
        ]);
    }

    /**
     * @Route("/panel/supervisor/delete/{id}", name="panel_supervisor_supr")
     * @param Supervisor $supervisor
     * @return Response
     */
    public function suppr(Supervisor $supervisor): Response
    {
        $this->getDoctrine()->getManager()->remove($supervisor);
        $this->getDoctrine()->getManager()->flush();
        return $this->redirectToRoute("panel_supervisor_list");
    }

    /**
     * @Route("/panel/supervisor/edit/{id}", name="panel_supervisor_edit")
     * @param Supervisor $supervisor
     * @param Request $request
     * @return Response
     */
    public function edit(Supervisor $supervisor, Request $request): Response
    {
        $form = $this->createForm(SupervisorType::class, $supervisor);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $this->getDoctrine()->getManager()->persist($supervisor);
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute("panel_supervisor_list");
        }
        return $this->render('supervisor/edit_supervisor.html.twig', [
            'form'=>$form->createView()
        ]);
    }
}
