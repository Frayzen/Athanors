<?php

namespace App\Controller;

use App\Entity\CalendarException;
use App\Entity\Rdv;
use App\Form\CalendarExceptionType;
use App\Form\ProfessionalType;
use App\Repository\AskCancelRepository;
use App\Repository\CalendarExceptionRepository;
use App\Repository\OvertimeRepository;
use App\Repository\RdvRepository;
use App\Repository\UserRepository;
use App\Repository\WorkTimeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * Class ProController
 * @package App\Controller
 * @IsGranted ("ROLE_PRO_AGENDA")
 */
class ProController extends AbstractController
{
    /**
     * @var int
     */
    private $rangePro = 28*1;


    /**
     * @Route("/panel/mypage/", name="panel_my_page")
     * @param Request $request
     * @param RdvRepository $rdvRepository
     * @return Response
     */
    public function home(Request $request, RdvRepository $rdvRepository)
    {
        $form = $this->createForm(ProfessionalType::class, $this->getUser()->getPro());
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $this->getDoctrine()->getManager()->flush();
        }
        return $this->render("panel/pages/myPage.html.twig", ['form'=>$form->createView()]);
    }


    /**
     * @Route("/panel/rdv/", name="panel_pro_rdv")
     * @param RdvRepository $rdvRepository
     * @return Response
     */
    public function rdv(RdvRepository $rdvRepository)
    {
        $invalidate = $rdvRepository->createQueryBuilder('r')
            ->orderBy('r.start', 'ASC')
            ->andWhere('r.validate = FALSE')
            ->andWhere('r.start > :now')
            ->setParameter(":now", new \DateTime())
            ->getQuery()
            ->getResult();
        $rdvs = $rdvRepository->createQueryBuilder('r')
            ->orderBy('r.start', 'ASC')
            ->andWhere('r.validate = TRUE')
            ->andWhere('r.start > :now')
            ->setParameter(":now", new \DateTime())
            ->getQuery()
            ->getResult();


        return $this->render("panel/pages/askRdv.html.twig", ["invalidate"=> $invalidate, "rdvs" => $rdvs]);
    }

    /**
     * @Route("/panel/rdv/weekly", name="panel_weekly")
     * @param WorkTimeRepository $workTimeRepository
     * @return Response
     */
    public function weekly(WorkTimeRepository $workTimeRepository)
    {
        return $this->render('panel/pages/weekly.html.twig', ['data'=>$workTimeRepository->getJSONedDatas($this->getUser()), "rangePro"=>$this->rangePro]);
    }

    /**
     * @Route("/panel/rdv/overtime", name="panel_overtime")
     * @param OvertimeRepository $overtimeRepository
     * @return Response
     */
    public function dispo_suppl(OvertimeRepository $overtimeRepository)
    {
        return $this->render('panel/pages/suppl.html.twig', ['data'=>$overtimeRepository->getJSONedDatas($this->getUser()), "rangePro"=>$this->rangePro]);
    }

    /**
     * @Route("/panel/rdv/cancel", name="panel_cancel")
     * @param RdvRepository $repository
     * @return Response
     */
    public function cancel(RdvRepository $repository)
    {
        $askCancelNotViewed = $this->getUser()->getPro()->getAskCancelNotViewed();
        $l = sizeof($askCancelNotViewed);
        foreach ($askCancelNotViewed as $nv){
            $nv->getAskCancel()->setViewed(true);
        }
        $this->getDoctrine()->getManager()->flush();

        return $this->render('panel/pages/cancel.html.twig', ['data'=>$repository->getJSONedDatas($this->getUser()), 'cancels'=>$this->getUser()->getPro()->getAskCancels(), "rangePro"=>$this->rangePro, 'viewedNow'=>($l > 0)]);
    }

    /**
     * @Route("/panel/rdv/exception", name="panel_exception")
     * @param Request $request
     * @param CalendarExceptionRepository $repository
     * @return Response
     */
    public function exception(Request $request, CalendarExceptionRepository $repository)
    {
        $em = $this->getDoctrine()->getManager();

        if ($request->get("deleteElem") != null) {
            $em->remove($repository->find($request->get("id")));
            $em->flush();
        }

        $calExc = new CalendarException();
        $form = $this->createForm(CalendarExceptionType::class, $calExc);
        $form->handleRequest($request);
        if ($form->isSubmitted() and $form->isValid()) {
            $calExc->setPro($this->getUser()->getPro());
            $em = $this->getDoctrine()->getManager();
            $em->persist($calExc);
            $em->flush($calExc);
        }

        return $this->render('panel/pages/exception.html.twig', ['actuals'=>$repository->findAllForse($this->getUser()->getPro()), "form"=>$form->createView()]);
    }

    /**
     * @Route("/panel/rdv/validate/{id}", name="panel_validate")
     * @param Rdv $rdv
     * @return Response
     */
    public function validate(Rdv $rdv)
    {
        $msg = "Le rendez vous du ";
        $msg.=$rdv->getStart()->format("d/m à H:i");
        $msg.=" auprès de ";
        $msg.=$rdv->getClient()->getFirstName();
        $msg.=" ";
        $msg.=$rdv->getClient()->getLastName();
        $msg.=" est désormais confirmé";
        $rdv->setValidate(true);
        $rdv->getClient()->setRegular(true);
        $rdv->setViewed(false);
        $em = $this->getDoctrine()->getManager();
        $em->persist($rdv);
        $em->persist($rdv->getClient());
        $em->flush();
        return $this->render("user/modal.twig", ["pathRedirect"=> $this->generateUrl("panel_pro"), "error"=>false, "message"=>$msg, 'title' => "Validation de rendez vous"]);
    }
}
