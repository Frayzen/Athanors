<?php

namespace App\Controller;

use App\Entity\Action;
use App\Entity\Page;
use App\Entity\Temoignage;
use App\Form\ActionType;
use App\Form\PageType;
use App\Form\TemoignageType;
use App\Repository\ActionRepository;
use App\Repository\PageRepository;
use App\Repository\TemoignageRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
/**
 * @IsGranted("ROLE_USER")
 */

class AdminController extends AbstractController
{
    /**
     * @return Response
     * @Route("/admin", name="admin")
     */
    public function admin(): Response
    {
        return $this->render('security/pages/home.html.twig');
    }
    /**
     * @return Response
     * @Route("/admin/temoignages", name="admin_tem_list")
     */
    public function temoignage_list(TemoignageRepository $temoignageRepository): Response
    {
        return $this->render('security/pages/temoignage/list.html.twig', [
            "tem_list"=>$temoignageRepository->findAll()
        ]);
    }

    /**
     * @param TemoignageRepository $temoignageRepository
     * @param Request $request
     * @return Response
     * @Route("/admin/temoignages/create", name="admin_create_tem")
     */
    public function create_tem(TemoignageRepository $temoignageRepository, Request $request): Response
    {
        $tem = new Temoignage();
        $form = $this->createForm(TemoignageType::class, $tem);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $tem->setCreation(new \DateTime());
            $this->getDoctrine()->getManager()->persist($tem);
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute("admin_tem_list");
        }

        return $this->render('security/pages/temoignage/temoignage_form.html.twig', [
            'form'=>$form->createView(),
            'name_page'=>"Création du témoignage"
        ]);
    }

    /**
     * @param Temoignage $temoignage
     * @param Request $request
     * @return Response
     * @Route("/admin/temoignages/edit/{id}", name="admin_edit_tem")
     */
    public function admin_edit_tem(Temoignage $tem, Request $request): Response
    {
        $form = $this->createForm(TemoignageType::class, $tem);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $this->getDoctrine()->getManager()->persist($tem);
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute("admin_tem_list");
        }

        return $this->render('security/pages/temoignage/temoignage_form.html.twig', [
            'form'=>$form->createView(),
            'name_page'=>"Edition du témoignage"
        ]);
    }
    /**
     * @param Temoignage $temoignage
     * @param Request $request
     * @return Response
     * @Route("/admin/temoignages/suppr/{id}", name="admin_suppr_tem")
     */
    public function admin_suppr_tem(Temoignage $tem, Request $request): Response
    {
        $this->getDoctrine()->getManager()->remove($tem);
        $this->getDoctrine()->getManager()->flush();
        return $this->redirectToRoute("admin_tem_list");
    }



    // ACTIONS


    /**
     * @param ActionRepository $actionRepository
     * @return Response
     * @Route("/admin/actions", name="admin_act_list")
     */
    public function action_list(ActionRepository $actionRepository): Response
    {
        return $this->render('security/pages/actions/list.html.twig', [
            "act_list"=>$actionRepository->findAll()
        ]);
    }

    /**
     * @param Action $action
     * @return Response
     * @Route("/admin/action/rm/{id}", name="admin_rm_act")
     */
    public function remove_act(Action $action): Response
    {
        $this->getDoctrine()->getManager()->remove($action);
        $this->getDoctrine()->getManager()->flush();
        return $this->redirectToRoute('admin_act_list');
    }


    /**
     * @param Action $act
     * @param Request $request
     * @return Response
     * @Route("/admin/actions/edit/{id}", name="admin_edit_act")
     */
    public function admin_edit_act(Action $act, Request $request): Response
    {
        $form = $this->createForm(ActionType::class, $act);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $this->getDoctrine()->getManager()->persist($act);
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute("admin_act_list");
        }

        return $this->render('security/pages/actions/action_form.html.twig', [
            'form'=>$form->createView(),
            'name_page'=>"Edition de l'action"
        ]);
    }

    /**
     * @param Request $request
     * @return Response
     * @Route("/admin/actions/create", name="admin_create_act")
     */
    public function admin_create_act(Request $request): Response
    {
        $act = new Action();
        $form = $this->createForm(ActionType::class, $act);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $this->getDoctrine()->getManager()->persist($act);
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute("admin_act_list");
        }

        return $this->render('security/pages/actions/action_form.html.twig', [
            'form'=>$form->createView(),
            'name_page'=>"Creation de l'action"
        ]);
    }

    // PAGES


    /**
     * @param PageRepository $pageRepository
     * @return Response
     * @Route("/admin/pages", name="admin_pages_list")
     */
    public function admin_pages_list(PageRepository $pageRepository): Response
    {
        return $this->render('security/pages/pages/list.html.twig', [
            "page_list"=>$pageRepository->findAll()
        ]);
    }

    /**
     * @param Page $page
     * @return Response
     * @Route("/admin/page/rm/{id}", name="admin_rm_page")
     */
    public function admin_rm_page(Page $page): Response
    {
        $this->getDoctrine()->getManager()->remove($page);
        $this->getDoctrine()->getManager()->flush();
        return $this->redirectToRoute('admin_page_list');
    }


    /**
     * @param Page $page
     * @param Request $request
     * @return Response
     * @Route("/admin/pages/edit/{id}", name="admin_edit_page")
     */
    public function admin_edit_page(Page $page, Request $request): Response
    {
        $form = $this->createForm(PageType::class, $page);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $this->getDoctrine()->getManager()->persist($page);
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute("admin_pages_list");
        }

        return $this->render('security/pages/pages/page_form.html.twig', [
            'form'=>$form->createView(),
            'name_page'=>"Edition de la page"
        ]);
    }

    /**
     * @param Request $request
     * @return Response
     * @Route("/admin/pages/create", name="admin_create_page")
     */
    public function admin_create_page(Request $request): Response
    {
        $page = new Page();
        $form = $this->createForm(PageType::class, $page);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $this->getDoctrine()->getManager()->persist($page);
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute("admin_pages_list");
        }

        return $this->render('security/pages/pages/page_form.html.twig', [
            'form'=>$form->createView(),
            'name_page'=>"Creation de la page"
        ]);
    }
}
