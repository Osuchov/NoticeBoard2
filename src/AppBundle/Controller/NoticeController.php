<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Notice;
use AppBundle\Form\NoticeType;
use DateTime;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


class NoticeController extends Controller
{
    /**
     * @Route("/")
     * @Template("AppBundle::myIndex.html.twig")
     */
    public function indexAction (Request $request)
    {
        $notice = new Notice();

        $form = $this->createForm(NoticeType::class, $notice);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($notice);
            $em->flush();

            return $this->redirectToRoute('app_notice_index');
        }

        $notices = $this
            ->getDoctrine()
            ->getRepository('AppBundle:Notice')
            ->findAll();

        return['form' => $form->createView(), 'notices' => $notices];
    }
}