<?php

namespace App\Controller;

use App\Entity\CancelledRdv;
use App\Entity\Overtime;
use App\Entity\Rdv;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class APICancelRDVController
 * @package App\Controller
 * @IsGranted ("ROLE_PRO_AGENDA")
 * @IsGranted("ROLE_PRO_AGENDA")
 */
class APICancelRDVController extends AbstractController
{
    /**
     * @Route("/api/cancel/rm", name="api_cancel_rm", methods={"PUT"})
     * @param Request $request
     * @return Response
     */
    public function rm(Request $request)
    {
        $datas = json_decode($request->getContent());
        $id = (int) $datas->id;
        $reason = $datas->reason;
        $em = $this->getDoctrine()->getManager();
        $rdv = $em->getRepository(Rdv::class)->find($id);

        if (!$rdv){
            return new Response('not found for '.$id, 404);
        }

        $cancelled = new CancelledRdv();

        $cancelled->setStart($rdv->getStart());
        $cancelled->setEnd($rdv->getEnd());
        $cancelled->setReason($reason);
        $cancelled->setUser($this->getUser());
        $cancelled->setPro($rdv->getPro());
        $cancelled->setViewed(false);

        $em->remove($rdv);
        $em->persist($cancelled);
        $em->flush();
        return new Response('OK', 200);
    }
}
