<?php

namespace App\Controller;

use App\Entity\Atelier;
use App\Entity\ExtraPage;
use App\Entity\Professional;
use App\Repository\AtelierRepository;
use App\Repository\ProfessionalRepository;
use App\Repository\RdvRepository;
use App\Repository\SupervisorRepository;
use App\Repository\WorkTimeRepository;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use function Sodium\add;

class PublicController extends AbstractController
{
    /*
     *
     * https://bootstrapmade.com/demo/Laura/
     *
     *
     */


    /**
     * @Route("/", name="home")
     */
    public function whoweare()
    {
        return $this->render("public/home.html.twig", [""]);
    }


    /**
     * @Route ("/consult/{id}", name="app_consult_pro_page")
     * @param Professional $professional
     * @return Response
     */
    public function info_pro(Professional $professional){
        return $this->render('public/analyste_page.html.twig', ["pro"=>$professional]);
    }

    /**
     * @Route ("/analyst/{id}", name="app_analyst")
     * @param Professional $professional
     * @return Response
     */
    public function analyst(Professional $professional){
        return $this->render('public/analyste.html.twig', ['pro_user' => $professional->getUser(), 'pro'=>$professional]);
    }



    /**
     * @Route ("/scheduler/{id}", name="app_scheduler")
     * @param int|null $id
     * @param ProfessionalRepository $adminRepository
     * @return Response
     * @throws Exception
    public function scheduler(int $id, ProfessionalRepository $adminRepository){
        $rangeDays = 56;
        if ($adminRepository->find($id) == null)
            return $this->redirectToRoute("home");
        $adminUser = $adminRepository->find($id)->getUser();
        $rdvs = $adminUser->getPro()->getRdvs();
        $overs = $adminUser->getPro()->getOvertimes();
        $workTimes = $adminUser->getPro()->getWorkTimes();
        $data = [];

        $idRdv = 0;

        $starts = [];

        foreach ($rdvs as $rdv) {
            $idRdv++;
            $starts[] = $rdv->getStart();
            $data[] = [
                'id'=>$idRdv,
                'start'=>$rdv->getStart()->format("Y-m-d H:i"),
                'end'=>$rdv->getEnd()->format("Y-m-d H:i") ,
                'title'=>'Indisponible',
                'backgroundColor'=>'red'
            ];
        }

        $exceptions = $adminUser->getPro()->getCalendarExceptions();
        foreach ($exceptions as $exc) {
            foreach ($overs as $over) {
                if ($over->getStart() <= $exc->getEnd() && $over->getStart() >= $exc->getStart())
                    $starts[] = $over->getStart();
                if ($over->getEnd() <= $exc->getEnd() && $over->getEnd() >= $exc->getStart())
                    $starts[] = $over->getEnd();
            }
        }
        foreach ($overs as $over) {
            if (!in_array($over->getStart(), $starts)) {
                $idRdv++;
                $starts[] = $over->getStart();
                $data[] = [
                    'id' => $idRdv,
                    'start' => $over->getStart()->format("Y-m-d H:i"),
                    'end' => $over->getEnd()->format("Y-m-d H:i"),
                    'title' => 'Exceptionnel',
                    'backgroundColor' => 'green'
                ];
            }
        }
        foreach ($workTimes as $wT) {
            $day = new \DateTime();
            foreach (range(0, $rangeDays-1) as $a) {
                $day = $day->add(date_interval_create_from_date_string("+1 day"));
                $formatStart = $day->format("Y-m-d")." ".$wT->getStart()->format("H:i");
                $formatEnd = $day->format("Y-m-d")." ".$wT->getEnd()->format("H:i");

                $start = new \DateTime($formatStart);
                $end = new \DateTime($formatEnd);

                foreach ($exceptions as $exc) {
                    if ($end <= $exc->getEnd() && $end >= $exc->getStart())
                        $starts[] = $end;
                    elseif ($start <= $exc->getEnd() && $start >= $exc->getStart())
                        $starts[] = $start;
                }

                if ($day->format("w") == $wT->getDay() and !in_array($start, $starts) and !in_array($end, $starts)){
                    $idRdv++;
                    $data[] = [
                        'id'=>$idRdv,
                        'start'=>$formatStart,
                        'end'=>$day->format("Y-m-d")." ".$wT->getEnd()->format("H:i"),
                        'title'=>'Disponible',
                        'backgroundColor' => 'green'
                    ];
                }
            }
        }
        foreach ($exceptions as $exc) {
            $idRdv++;
            $data[] = [
                'id'=>$idRdv,
                'start'=>$exc->getStart()->format("Y-m-d H:i"),
                'end'=>$exc->getEnd()->format("Y-m-d H:i"),
                'title'=>'Absent',
                'display'=>'background'
            ];
        }

        $data = json_encode($data);
        return $this->render("public/scheduler.html.twig", ["admins"=> $adminRepository->findAll(), 'admin' => $adminRepository->find($id), 'actualAdminUser'=>$adminUser, "data"=>$data, "rangeDays"=>$rangeDays]);
    }

     */

    /**
     * @Route ("/analystes", name="app_analystes")
     * @param ProfessionalRepository $adminRepository
     * @return Response
     */
    public function schedulers(ProfessionalRepository $adminRepository){
        return $this->render("public/analystes.html.twig", [
            'admins'=>$adminRepository->findAll()
        ]);
    }

    /**
     * @Route ("/ateliers", name="list_atelier")
     * @param AtelierRepository $atelierRepository
     * @return Response
     */
    public function ateliers_list(AtelierRepository $atelierRepository){
        return $this->render('public/list_ateliers.html.twig', ["list_atelier"=>$atelierRepository->findAllFromNow()]);
    }

    /**
     * @Route ("/intervenants", name="list_intervenants")
     * @param SupervisorRepository $supervisorRepository
     * @return Response
     */
    public function intervenants_list(SupervisorRepository $supervisorRepository){
        return $this->render('public/list_intervenants.html.twig', ["list_intervenants"=>$supervisorRepository->findAll()]);
    }
    /**
     * @Route("/atelier/{id}", name="atelier_consult")
     * @param Atelier $atelier
     * @return Response
     */
    public function show(Atelier $atelier): Response
    {
        return $this->render('public/atelier.html.twig', ['atelier'=>$atelier]);
    }

    /**
     * @Route("/travailensoivoyageensoi", name="travail_voyage")
     * @return Response
     */
    public function travail_voyage(): Response
    {
        return $this->render('public/travail_voyage.html.twig' );
    }

    /**
     * @Route("/approche/jungienne", name="approche_jungienne")
     * @return Response
     */
    public function approcheJungienne(): Response
    {
        return $this->render('public/approche_jungienne.html.twig');
    }

    /**
     * @Route("/page/{id}", name="extra_page")
     * @param ExtraPage $page
     * @return Response
     */
    public function extraPage(ExtraPage $page): Response
    {
        return $this->render('public/extra_page.html.twig', ['page'=>$page]);
    }
}
