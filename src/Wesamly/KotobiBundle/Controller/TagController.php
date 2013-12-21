<?php
namespace Wesamly\KotobiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use Wesamly\KotobiBundle\Entity\Tag;
use Wesamly\KotobiBundle\Form\Type\TagType;

class TagController extends Controller
{
    public function indexAction()
    {
        $tags = $this->getDoctrine()
            ->getRepository('WesamlyKotobiBundle:Tag')
            ->findAll();

        return $this->render('WesamlyKotobiBundle:Tag:index.html.twig', array(
                'tags'=>$tags,
            ));
    }

    public function addAction(Request $request)
    {
        $form = $this->createForm(new TagType(), new Tag());

        $form->handleRequest($request);
        if ($form->isValid()) {
            $tag = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($tag);
            $em->flush();

            return $this->redirect($this->generateUrl('wesamly_kotobi_taglist'));
        }

        return $this->render('WesamlyKotobiBundle:Tag:edit.html.twig', array(
                'form' => $form->createView()
            ));
    }

    public function editAction($id, Request $request)
    {
        $tag = $this->getDoctrine()
            ->getRepository('WesamlyKotobiBundle:Tag')
            ->find($id);

        if (!$tag) {
            throw $this->createNotFoundException(
                'No tag found for id '.$id
            );
        }
        $form = $this->createForm(new TagType(), $tag);

        $form->handleRequest($request);
        if ($form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($tag);
            $em->flush();

            return $this->redirect($this->generateUrl('wesamly_kotobi_taglist'));
        }

        return $this->render('WesamlyKotobiBundle:Tag:edit.html.twig', array(
                'form' => $form->createView()
            ));
    }

    public function deleteAction($id, Request $request)
    {

        $tag = $this->getDoctrine()
            ->getRepository('WesamlyKotobiBundle:Tag')
            ->find($id);

        if (!$tag) {
            throw $this->createNotFoundException(
                'No category found for id '.$id
            );
        }

        $form = $this->createFormBuilder(array())
            ->add('id', 'hidden', array('data'=>$tag->getId()))
            ->add('confirm','submit')
            ->add('cancel','submit')
            ->getForm();

        $form->handleRequest($request);

        if ($form->isValid() && $request->getMethod()=='POST') {

            if($form->get('confirm')->isClicked()) {
                //delete
                $tag = $this->getDoctrine()
                    ->getRepository('WesamlyKotobiBundle:Tag')
                    ->find($id);
                $em = $this->getDoctrine()->getManager();
                $em->remove($tag);
                $em->flush();
            }
            return $this->redirect($this->generateUrl('wesamly_kotobi_taglist'));

        }
        return $this->render('WesamlyKotobiBundle:Tag:delete.html.twig', array(
                'form' => $form->createView(),
                'tag' => $tag
            ));
    }

}