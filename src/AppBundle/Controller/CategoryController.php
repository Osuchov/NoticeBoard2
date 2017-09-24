<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Category;
use AppBundle\Form\CategoryType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class CategoryController extends Controller
{
    /**
     * @Route("/newCategory")
     * @Template("AppBundle::newCategory.html.twig")
     */
    public function newCategoryAction(Request $request)
    {
        $category = new Category();
        $user = $this->getUser();

        if(!$user->hasRole('ROLE_ADMIN')) {
            return $this->render('@App/wrongTurn.html.twig');
        }

        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();

            return $this->redirectToRoute('app_user_admineditcategories');
        }

        return['form' => $form->createView()];
    }


    /**
     * @Route("/delCategory/{id}")
     */
    public function deleteCategoryAction ($id)
    {
        $user = $this->getUser();

        if(!$user->hasRole('ROLE_ADMIN')) {
            return $this->render('@App/wrongTurn.html.twig');
        }

        $category = $this
            -> getDoctrine()
            -> getRepository('AppBundle:Category')
            -> find($id);

        if (!$category) {
            throw $this->createNotFoundException('Category not found');
        }

        $em = $this
            ->getDoctrine()
            ->getManager();

        $em->remove($category);
        $em->flush();

        return $this->redirectToRoute('app_user_admineditcategories');
    }

    /**
     * @Route("/editCategory/{id}")
     * @Template("@App/newCategory.html.twig")
     */
    public function editCategoryAction(Request $request, $id)
    {
        $user = $this->getUser();

        if(!$user->hasRole('ROLE_ADMIN')) {
            return $this->render('@App/wrongTurn.html.twig');
        }

        $category = $this->getDoctrine()
            ->getRepository('AppBundle:Category')
            ->find($id);

        if(!$category) {
            throw $this->createNotFoundException('Category not found');
        }

        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            return $this->redirectToRoute('app_user_admineditcategories');
        }

        return['form' => $form->createView()];
    }
}