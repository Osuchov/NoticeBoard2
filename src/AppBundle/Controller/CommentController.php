<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Comment;
use AppBundle\Form\CommentType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CommentController extends Controller
{
    /**
     * @Route("/newComment/{id}")
     * @Template("AppBundle::newComment.html.twig")
     */
    public function newCommentAction(Request $request, $id)
    {
        $comment = new Comment();

        $user = $this->getUser();

        if (!$user) {
            throw $this->createNotFoundException('User not found.');
        }

        $notice = $this
            ->getDoctrine()
            ->getRepository('AppBundle:Notice')
            ->find($id);

        if (!$notice) {
            throw $this->createNotFoundException('Notice not found.');
        }

        $comment->setNoticeId($notice);
        $comment->setUser($user);
        $notice->addComment($comment);
        $user->addComment($comment);

        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($comment);
            $em->flush();

            return $this->redirectToRoute('app_notice_index');
        }

        return['form' => $form->createView(), 'notice' => $notice, 'comment' => $comment, 'user' => $user];
    }

    /**
     * @Route("/delComment/{id}")
     */
    public function deleteCommentAction($id)
    {
        $comment = $this
            ->getDoctrine()
            ->getRepository('AppBundle:Comment')
            ->find($id);

        if (!$comment) {
            throw $this->createNotFoundException('Comment not found');
        }

        if ($this->getUser() == $comment->getUser()) {
            $em = $this
                ->getDoctrine()
                ->getManager();

            $em->remove($comment);
            $em->flush();

            return $this->redirectToRoute('app_notice_index');
        }
        else {
            //throw $this->createNotFoundException('This is not your comment. Vay yu do dis?');
            //return new Response ("<html><body>This is not your comment. Vay yu do dis?</body></html>");
            return $this->render('@App/wrongTurn.html.twig');
        }
    }

    /**
     * @Route("/editComment/{id}")
     * @Template("AppBundle::newComment.html.twig")
     */
    public function editCommentAction(Request $request, $id)
    {
        $comment = $this
            ->getDoctrine()
            ->getRepository('AppBundle:Comment')
            ->find($id);

        if (!$comment) {
            throw $this->createNotFoundException('Comment not found');
        }

        $notice = $comment->getNotice();

        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            return $this->redirectToRoute('app_notice_index');
        }
        return['form' => $form->createView(), 'notice' => $notice, 'comment' => $comment];
    }
}