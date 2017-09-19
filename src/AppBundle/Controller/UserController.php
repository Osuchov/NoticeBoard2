<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;

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
}