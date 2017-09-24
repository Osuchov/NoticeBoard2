<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Notice;
use AppBundle\Form\NoticeType;
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
        $notices = $this
            ->getDoctrine()
            ->getRepository('AppBundle:Notice')
            ->findAll();

        return['notices' => $notices];
    }

    /**
     * @Route("/newNotice")
     * @Template("AppBundle::newNotice.html.twig")
     */
    public function newNoticeAction(Request $request)
    {
        $notice = new Notice();
        $user = $this->getUser();

        if (!$user) {
            throw $this->createNotFoundException('User not found.');
        }

        $notice->setUser($user);
        $user->addNotice($notice);

        $form = $this->createForm(NoticeType::class, $notice);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($notice);
            $em->flush();

            return $this->redirectToRoute('app_notice_index');
        }

        return['form' => $form->createView()];
    }

    /**
     * @Route("/delNotice/{id}")
     */
    public function deleteNoticeAction ($id)
    {
        $notice = $this
            -> getDoctrine()
            -> getRepository('AppBundle:Notice')
            -> find($id);

        if (!$notice) {
            throw $this->createNotFoundException('Notice not found');
        }

        if ($this->getUser() == $notice->getUser()) {
            $em = $this
                ->getDoctrine()
                ->getManager();

            $em->remove($notice);
            $em->flush();

            return $this->redirectToRoute('app_notice_index');
        }
        else {
            return $this->render('@App/wrongTurn.html.twig');
        }
    }

    /**
     * @Route("/editNotice/{id}")
     * @Template("@App/newNotice.html.twig")
     */
    public function editNoticeAction(Request $request, $id)
    {
        $notice = $this->getDoctrine()
            ->getRepository('AppBundle:Notice')
            ->find($id);

        if(!$notice) {
            throw $this->createNotFoundException('Notice not found');
        }

        if ($this->getUser() == $notice->getUser()) {
        $form = $this->createForm(NoticeType::class, $notice);
        $form->handleRequest($request);

            if($form->isSubmitted() && $form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->flush();

                return $this->redirectToRoute('app_notice_index');
            }

        }
        else {
            return $this->render('@App/wrongTurn.html.twig');
        }

        return['form' => $form->createView()];
    }
}