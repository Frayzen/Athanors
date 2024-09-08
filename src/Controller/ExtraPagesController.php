<?php

namespace App\Controller;

use App\Entity\Atelier;
use App\Entity\ExtraPage;
use App\Form\ExtraPageType;
use App\Repository\ExtraPageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ExtraPagesController extends AbstractController
{
    /**
     * @Route("/panel/pages/", name="panel_extra_pages")
     * @param ExtraPageRepository $repository
     * @return Response
     */
    public function extraPagesList(ExtraPageRepository $repository): Response
    {
        return $this->render("extra_pages/list_pages.html.twig", ["pages"=>$repository->findAll()]);
    }

    /**
     * @Route("/panel/create/", name="panel_extra_pages_create")
     * @param Request $request
     * @return Response
     */
    public function extraPagesCreate(Request $request): Response
    {
        $extraPage = new ExtraPage();

        $form = $this->createForm(ExtraPageType::class, $extraPage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $this->getDoctrine()->getManager()->persist($extraPage);
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute("panel_extra_pages");
        }
        return $this->render('extra_pages/create_extra_page.html.twig', [
            'form'=>$form->createView()
        ]);
    }

    /**
     * @Route("/panel/pages/edit/{id}", name="panel_extra_pages_edit")
     * @param ExtraPage $extraPage
     * @param Request $request
     * @return Response
     */
    public function extraPagesEdit(ExtraPage $extraPage, Request $request): Response
    {

        $form = $this->createForm(ExtraPageType::class, $extraPage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $this->getDoctrine()->getManager()->persist($extraPage);
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute("panel_extra_pages");
        }
        return $this->render('extra_pages/edit_extra_page.html.twig', [
            'form'=>$form->createView()
        ]);
    }

    /**
     * @Route("/panel/pages/delete/{id}", name="panel_extra_pages_suppr")
     * @param ExtraPage $extraPage
     * @return Response
     */
    public function suppr(ExtraPage $extraPage): Response
    {
        $man = $this->getDoctrine()->getManager();
        $man->remove($extraPage);
        $man->flush();
        return $this->redirectToRoute("panel_extra_pages");
    }
}
