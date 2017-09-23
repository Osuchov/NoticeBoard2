<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;


class UserController extends Controller
{
    /**
     * @Route("/admin")
     * @Template("@App/admin.html.twig")
     */
    public function adminPanelAction()
    {
        $user = $this->getUser();

        if(!$user->hasRole('ROLE_ADMIN')) {
            return $this->render('@App/wrongTurn.html.twig');
        }
    }

    /**
     * @Route("/user")
     * @Template("@App/user.html.twig")
     */
    public function userPanelAction()
    {
        $user = $this->getUser();

        if (!$user) {
            throw $this->createNotFoundException('User not found.');
        }

        return ['user'=>$user];
    }

    /**
     * @Route("/userNotices")
     * @Template("@App/userNotices.html.twig")
     */
    public function showUserNoticesAction()
    {
        $user = $this->getUser();

        if (!$user) {
            throw $this->createNotFoundException('User not found.');
        }

        $notices = $this
            ->getDoctrine()
            ->getRepository('AppBundle:Notice')
            ->findBy(['user'=>$this->getUser()]);

        return ['user'=>$user, 'notices'=>$notices];
    }

    /**
     * @Route("/userComments")
     * @Template("@App/userComments.html.twig")
     */
    public function showUserCommentsAction()
    {
        $user = $this->getUser();

        if (!$user) {
            throw $this->createNotFoundException('User not found.');
        }

        $comments = $this
            ->getDoctrine()
            ->getRepository('AppBundle:Comment')
            ->findBy(['user'=>$this->getUser()]);

        return ['user'=>$user, 'comments'=>$comments];
    }


}