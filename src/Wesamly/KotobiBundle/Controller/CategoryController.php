<?php
namespace Wesamly\KotobiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use Wesamly\KotobiBundle\Entity\Category;
use Wesamly\KotobiBundle\Form\Type\CategoryType;

class CategoryController extends Controller
{
    public function indexAction()
    {
        $categories = $this->getDoctrine()
            ->getRepository('WesamlyKotobiBundle:Category')
            ->findAll();

        return $this->render('WesamlyKotobiBundle:Category:index.html.twig', array(
                'categories'=>$categories,
            ));
    }

    public function addAction(Request $request)
    {
        $form = $this->createForm(new CategoryType(), new Category());

        $form->handleRequest($request);
        if ($form->isValid()) {
            $category = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();

            return $this->redirect($this->generateUrl('wesamly_kotobi_categorylist'));
        }

        return $this->render('WesamlyKotobiBundle:Category:edit.html.twig', array(
                'form' => $form->createView()
            ));
    }

    public function editAction($id, Request $request)
    {
        $category = $this->getDoctrine()
            ->getRepository('WesamlyKotobiBundle:Category')
            ->find($id);

        if (!$category) {
            throw $this->createNotFoundException(
                'No category found for id '.$id
            );
        }
        $form = $this->createForm(new CategoryType(), $category);

        $form->handleRequest($request);
        if ($form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();

            return $this->redirect($this->generateUrl('wesamly_kotobi_categorylist'));
        }

        return $this->render('WesamlyKotobiBundle:Category:edit.html.twig', array(
                'form' => $form->createView()
            ));
    }

    public function deleteAction($id, Request $request)
    {

        $category = $this->getDoctrine()
            ->getRepository('WesamlyKotobiBundle:Category')
            ->find($id);

        if (!$category) {
            throw $this->createNotFoundException(
                'No category found for id '.$id
            );
        }

        $form = $this->createFormBuilder(array())
            ->add('id', 'hidden', array('data'=>$category->getId()))
            ->add('confirm','submit')
            ->add('cancel','submit')
            ->getForm();

        $form->handleRequest($request);

        if ($form->isValid() && $request->getMethod()=='POST') {

            if($form->get('confirm')->isClicked()) {
                //delete
                $category = $this->getDoctrine()
                    ->getRepository('WesamlyKotobiBundle:Category')
                    ->find($id);
                $em = $this->getDoctrine()->getManager();
                $em->remove($category);
                $em->flush();
            }
            return $this->redirect($this->generateUrl('wesamly_kotobi_categorylist'));

        }
        return $this->render('WesamlyKotobiBundle:Category:delete.html.twig', array(
                'form' => $form->createView(),
                'category' => $category
            ));
    }

}