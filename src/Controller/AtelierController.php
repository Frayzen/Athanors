<?php

namespace App\Controller;

use App\Entity\Atelier;
use App\Form\AtelierType;
use App\Repository\AtelierRepository;
use App\Repository\AtelierSessionRepository;
use App\Repository\SupervisorRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
* @IsGranted ("ROLE_PRO_ATELIER")
**/
class AtelierController extends AbstractController
{
    /**
     * @Route("/panel/atelier", name="panel_atelier")
     * @param AtelierRepository $atelierRepository
     * @return Response
     */
    public function index(AtelierRepository $atelierRepository): Response
    {
        return $this->render('atelier/list_ateliers.html.twig', [
            'list_atelier'=>$atelierRepository->findAll()
        ]);
    }

    /**
     * @Route("/panel/atelier/delete/{id}", name="panel_atelier_suppr")
     * @param Atelier $atelier
     * @return Response
     */
    public function suppr(Atelier $atelier): Response
    {
        $man = $this->getDoctrine()->getManager();
        foreach ($atelier->getAtelierSessions() as $atelierSession){
            foreach ($atelierSession->getAnswerUserSessions() as $answerUserSession) {
                $man->remove($answerUserSession);
            }
            $man->remove($atelierSession);
        }
        $atelier->getSupervisors()->clear();
        $man->remove($atelier);
        $man->flush();
        return $this->redirectToRoute("panel_atelier");
    }

    /**
     * @Route("/panel/atelier/create", name="panel_atelier_create")
     * @param Request $request
     * @return Response
     */
    public function create(Request $request): Response
    {
        $atelier = new Atelier();

        $form = $this->createForm(AtelierType::class, $atelier);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $this->getDoctrine()->getManager()->persist($atelier);
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute("panel_atelier");
        }
        return $this->render('atelier/create_atelier.html.twig', [
            'form'=>$form->createView()
        ]);
    }

    /**
     * @Route("/panel/atelier/edit/{id}", name="panel_atelier_edit")
     * @param Atelier $atelier
     * @param Request $request
     * @return Response
     */
    public function edit(Atelier $atelier, Request $request): Response
    {
        $form = $this->createForm(AtelierType::class, $atelier);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $this->getDoctrine()->getManager()->persist($atelier);
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute("panel_atelier");
        }
        return $this->render('atelier/edit_atelier.html.twig', [
            'form'=>$form->createView()
        ]);
    }

    /**
     * @Route("/panel/atelier/agenda/edit/{id}", name="panel_atelier_agenda_edit")
     * @param Atelier $atelier
     * @param AtelierSessionRepository $atelierSessionRepository
     * @return Response
     */
    public function edit_agenda(Atelier $atelier, AtelierSessionRepository $atelierSessionRepository): Response
    {
        return $this->render("atelier/edit_agenda.html.twig", ['atelier'=>$atelier, 'data'=>$atelierSessionRepository->getJSONedDatas($atelier)]);
    }

    /**
     * @Route("/panel/atelier/supervisors/edit/{id}", name="panel_atelier_supervisors_edit")
     * @param Atelier $atelier
     * @param SupervisorRepository $repository
     * @return Response
     */
    public function edit_supervisors(Atelier $atelier, SupervisorRepository $repository) : Response
    {
        return $this->render("atelier/edit_supervisors.html.twig", ['atelier'=>$atelier, "supervisors"=>$repository->findAll()]);
    }
}
