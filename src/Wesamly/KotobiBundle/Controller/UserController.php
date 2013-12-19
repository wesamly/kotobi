<?php

namespace Wesamly\KotobiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Wesamly\KotobiBundle\Entity\User;
use Wesamly\KotobiBundle\Form\Type\UserType;


class UserController extends Controller
{
    public function indexAction()
    {

        $users = $this->getDoctrine()
            ->getRepository('WesamlyKotobiBundle:User')
            ->findAll();

        return $this->render('WesamlyKotobiBundle:User:index.html.twig', array(
                'users'=> $users,
            ));
    }

    public function addAction(Request $request)
    {
        $form = $this->createForm(new UserType(), new User());

        $form->handleRequest($request);

        if ($form->isValid()) {
            $user = $form->getData();

            //Encode Password
            if($user->getPassword()!=''){
                $factory = $this->get('security.encoder_factory');
                $encoder = $factory->getEncoder($user);
                $password = $encoder->encodePassword($user->getPassword(), $user->getSalt());
                $user->setPassword($password);
            }

            //Save user
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            //ToDo: Set Flash Message
            return $this->redirect($this->generateUrl('wesamly_kotobi_userlist'));
        }


        return $this->render('WesamlyKotobiBundle:User:edit.html.twig', array(
                'form' => $form->createView()
            ));
    }

    public function editAction($id, Request $request)
    {

        $user = $this->getDoctrine()
            ->getRepository('WesamlyKotobiBundle:User')
            ->find($id);

        if (!$user) {
            throw $this->createNotFoundException(
                'No user found for id '.$id
            );
        }

        $form = $this->createForm(new UserType(), $user);
        $form->handleRequest($request);

        if ($form->isValid()) {


            //Encode Password, if specified
            if( $form->get('password')->getData() != ''){

                $user->setSalt(md5(uniqid(null, true)));
                $factory = $this->get('security.encoder_factory');
                $encoder = $factory->getEncoder($user);
                $password = $encoder->encodePassword($user->getPassword(), $user->getSalt());
                $user->setPassword($password);
            }
            //Save user details
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            //Success Message
            $this->get('session')->getFlashBag()->add(
                'success',
                'User details updated'
            );
            //Return to Users List
            return $this->redirect($this->generateUrl('wesamly_kotobi_userlist'));
        }
        return $this->render('WesamlyKotobiBundle:User:edit.html.twig', array(
                'form' => $form->createView(),
            ));
    }

    public function deleteAction($id, Request $request)
    {

        $user = $this->getDoctrine()
            ->getRepository('WesamlyKotobiBundle:User')
            ->find($id);

        if (!$user) {
            throw $this->createNotFoundException(
                'No user found for id '.$id
            );
        }

        $form = $this->createFormBuilder(array())
            ->add('id', 'hidden', array('data'=>$user->getId()))
            ->add('confirm','submit')
            ->add('cancel','submit')
            ->getForm();

        $form->handleRequest($request);

        if ($form->isValid() && $request->getMethod()=='POST') {

            if($form->get('confirm')->isClicked()) {
                //delete
                $user = $this->getDoctrine()
                    ->getRepository('WesamlyKotobiBundle:User')
                    ->find($id);
                $em = $this->getDoctrine()->getManager();
                $em->remove($user);
                $em->flush();
                $this->get('session')->getFlashBag()->add(
                    'success',
                    'User deleted'
                );
            }
            return $this->redirect($this->generateUrl('wesamly_kotobi_userlist'));

        }
        return $this->render('WesamlyKotobiBundle:User:delete.html.twig', array(
                'form' => $form->createView(),
                'user' => $user
            ));
    }
}
