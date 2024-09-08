<?php

namespace App\Controller;

use App\Entity\AnswerUserSession;
use App\Entity\AskCancel;
use App\Entity\Atelier;
use App\Entity\Rdv;
use App\Form\AskCancelType;
use App\Repository\AtelierRepository;
use App\Repository\AtelierSessionRepository;
use App\Repository\CancelledRdvRepository;
use App\Repository\RdvRepository;
use App\Repository\UserRepository;
use App\Repository\WorkTimeRepository;
use DateTime;
use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class UserController
 * @package App\Controller
 * @IsGranted ("ROLE_USER")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/user/rdvs/cancel/{id}", name="user_cancel_rdv")
     * @param Rdv $rdv
     * @param Request $request
     * @return RedirectResponse|Response
    public function cancelRdv(Rdv $rdv, Request $request){

        $askCancel = new AskCancel();
        $form = $this->createForm(AskCancelType::class, $askCancel);
        $form->handleRequest($request);
        if ($form->isSubmitted() and $form->isValid()){
            if ($rdv->getAskCancel() != null){
                return $this->render("user/modal.twig", ["pathRedirect"=>$this->generateUrl("user_rdvs"), "message"=>"Ce rendez vous a déjà une demande d'annulation en cour", "error"=>true, 'title' => "Impossible de demander l'annulation"]);
            }
            $askCancel->setRdv($rdv);
            $askCancel->setViewed(false);
            $em = $this->getDoctrine()->getManager();
            $em->persist($askCancel);
            $em->flush();
            return $this->render("user/modal.twig", ["pathRedirect"=>$this->generateUrl("user_rdvs"), "message"=>"Demande d'annulation envoyé avec succés", "error"=>false, 'title' => "Impossible de demander l'annulation"]);
        }
        if ($rdv->getClient() != $this->getUser())
            return $this->redirect($this->generateUrl("home"));
        return $this->render("user/askCancelRdv.html.twig", ["rdv"=>$rdv, "form"=>$form->createView()]);
    }



    /**
     * @Route("/user/rdvs", name="user_rdvs")
     * @param RdvRepository $repository
     * @param CancelledRdvRepository $cancelledRdvRepository
     * @return Response
    public function user_rdvs(RdvRepository $repository, CancelledRdvRepository $cancelledRdvRepository){
        $viewed = $cancelledRdvRepository->findElemsViewed($this->getUser(), true);
        $notViewed = $cancelledRdvRepository->findElemsViewed($this->getUser(), false);
        foreach ($notViewed as $nv){
            $nv->setViewed(true);
        }
        $em = $this->getDoctrine()->getManager();
        $em->flush();
        return $this->render("user/my_rdvs.html.twig", ['rdvs'=>$repository->findAllAfterNow(), "cancelleds"=>$notViewed,'cancelledsViewed'=>$viewed]);
    }
*/


    /**
     * @Route("/user/ateliers", name="user_ateliers")
     * @param AtelierRepository $repository
     * @return Response
     */
    public function user_ateliers(AtelierRepository $repository){
        $ateliers = [];
        foreach ($repository->findAll() as $atelier){
            if ($atelier->getMembers()->contains($this->getUser()))
                array_push($ateliers, $atelier);
        }
        return $this->render("user/my_ateliers.html.twig", ['ateliers'=>$ateliers]);
    }

    /**
     * @Route("/user/atelier/presence", name="api_user_ateliers_presence", methods={"PUT"})
     *
     * @param AtelierSessionRepository $repository
     * @param Request $request
     */
    public function api_user_ateliers_presence(AtelierSessionRepository $repository, Request $request){
        $datas = json_decode($request->getContent());
        $idAtelier = (int) $datas->idAtelier;
        $idSession = (int) $datas->idSession;
        $answerValue = (bool) $datas->answerValue;
        $session = $repository->findOneBy(['atelier'=>$idAtelier, 'calendarId'=>$idSession]);
        if ($session->getAtelier()->getSessionsMandatory())
            return new Response("Cannot define your choice", 500);
        $answer = null;
        foreach ($session->getAnswerUserSessions() as $answers){
            if ($answers->getUser() == $this->getUser()){
                $answers->setPresence($answerValue);
                $answer = $answers;
            }
        }
        if ($answer == null) {
            $answer = new AnswerUserSession();
            $answer->setSession($session)->setUser($this->getUser())->setPresence($answerValue);
            $this->getDoctrine()->getManager()->persist($answer);
        }
        $this->getDoctrine()->getManager()->flush();
        return new Response("OK", 200);
    }





    /**
     * @Route("/rdv", name="reservation_date")
     * @param Request $request
     * @param UserRepository $userRepository
     * @param WorkTimeRepository $workTimeRepository
     * @return Response
    public function index(Request $request, UserRepository $userRepository, WorkTimeRepository $workTimeRepository)
    {
        $idAdminUser = $request->get('idAdminUser');
        $start = DateTime::createFromFormat('D M d Y H:i:s e+', $request->get('start'));
        $end = DateTime::createFromFormat('D M d Y H:i:s e+', $request->get('end'));
        return $this->render('user/reserv.html.twig', ['end'=>$end, 'start'=>$start, 'pro'=>$userRepository->find($idAdminUser)]);
    }

    /**
     * @Route ("/valid", name="reservation_valid")
     * @param Request $request
     * @param WorkTimeRepository $workTimeRepository
     * @param UserRepository $userRepository
     * @return Response
     * @throws Exception
    public function valid(Request  $request, RdvRepository $rdvRepository, WorkTimeRepository $workTimeRepository, UserRepository $userRepository){
        $pro = $userRepository->find($request->get('idPro'));
        $start = new \DateTime($request->get('start'));
        $end = new \DateTime($request->get('end'));
        $user = $this->getUser();
        if (sizeof($user->getRdvs()) >= 1 and !$user->getRegular())
            return $this->render("user/modal.twig", ["pathRedirect"=>$this->generateUrl("user_rdvs"),"error"=>true, "message"=>"Vous avez déjà un rendez vous en attente et vous n'êtes pas encore considéré comme un patient régulier, vous ne pouvez donc pas prendre plus d'un seul rendez vous à la fois. Pardonnez nous pour le désagrément", 'title' => "Impossible de prendre un nouveau rendez-vous"]);
        $em = $this->getDoctrine()->getManager();
        if ($em->getRepository(Rdv::class)->findOneBy(['start'=>$start, "end"=>$end, "pro"=>$pro], []) != null)
            return $this->render("user/modal.twig", ["pathRedirect"=> $this->generateUrl("home"), "error"=>true, "message"=>"Une erreur est survenue. Pardonnez nous pour le désagrément", 'title' => "Impossible de prendre un nouveau rendez-vous"]);

        $checkWorkTime = false;

        foreach ($pro->getPro()->getWorkTimes() as $workTime) {
            if ($workTime->getDay() == $start->format("w") and $workTime->getStart()->format("H:i:s") == $start->format("H:i:s"))
                $checkWorkTime = true;
        }

        $checkOverTime = false;

        foreach ($pro->getPro()->getOvertimes() as $overtime) {
            if ($overtime->getStart()->format("Y-m-d H:i") == $start->format("Y-m-d H:i") and $overtime->getEnd()->format("Y-m-d H:i") == $end->format("Y-m-d H:i"))
                $checkOverTime = true;
        }

        $checkRdvTaken = false;
        if ($rdvRepository->findOneBy(['pro'=>$pro->getId(), 'start'=>$start, 'end'=>$end]) != null)
            $checkRdvTaken = true;

        if ((!$checkWorkTime and !$checkOverTime) or $checkRdvTaken)
            return $this->render("user/modal.twig", ["pathRedirect"=> $this->generateUrl("home"), "error"=>true, "message"=>"Une erreur est survenue. Pardonnez nous pour le désagrément", "title"=>"Oups..."]);

        $rdv = new Rdv();
        $rdv->setClient($this->getUser());
        $rdv->setPro($pro->getPro());
        $rdv->setValidate(false);
        $rdv->setStart($start);
        $rdv->setEnd($end);

        $em->persist($rdv);
        $em->flush();

        return $this->render("user/modal.twig", ["pathRedirect"=>$this->generateUrl("user_rdvs"), "error"=>false, "message"=>"Le rendez-vous à été pris avec succés", "title"=>"Validation du rendez-vous"]);
    }
     */

    /**
     * @Route ("/atelier/{id}/register", name="register_atelier")
     * @param Atelier $atelier
     * @return Response
     */
    public function register_atelier(Atelier $atelier): Response
    {
        if (!$atelier->isAccesible())
            return $this->redirectToRoute("atelier_consult", ['id'=>$atelier->getId()]);
        return $this->render("user/register_atelier.html.twig", ['atelier'=>$atelier]);
    }


    /**
     * @Route ("/atelier/register/valid", name="atelier_register_valid")
     * @param Request $request
     * @param AtelierRepository $atelierRepository
     * @return Response
     */
    public function atelier_register_valid(Request $request, AtelierRepository $atelierRepository){
        $atelier = $atelierRepository->find($request->get('idAtelier'));
        if (!$atelier->isAccesible())
            return $this->redirectToRoute("atelier_consult", ['id'=>$atelier->getId()]);
        if ($atelier->getMembers()->contains($this->getUser()))
            return $this->render("user/modal.twig", ["pathRedirect"=> $this->generateUrl("atelier_consult", ['id'=>$atelier->getId()]), "error"=>true, "message"=>"Vous ne pouvez pas rejoindre deux fois le même atelier, une précédente inscription a été effectuée.", 'title' => "Vous faites déjà parti de cet atelier"]);
        if (sizeof($atelier->getMembers()) >= $atelier->getMaxUser())
            return $this->render("user/modal.twig", ["pathRedirect"=> $this->generateUrl("atelier_consult", ['id'=>$atelier->getId()]), "error"=>true, "message"=>"L'atelier que vous souhaitez rejoindre est plein, pardonnez nous pour le désagrément", 'title' => "Atelier plein"]);
        $atelier->getMembers()->add($this->getUser());
        $this->getDoctrine()->getManager()->persist($atelier);
        $this->getDoctrine()->getManager()->flush();

        return $this->render("user/modal.twig", ["pathRedirect"=>$this->generateUrl("home"), "error"=>false, "message"=>"Vous avez correctement rejoint l'atelier", "title"=>"Validation de l'inscription"]);
    }
}
