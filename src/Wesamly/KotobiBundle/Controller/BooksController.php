<?php

namespace Wesamly\KotobiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\DateTime;
use Wesamly\KotobiBundle\Entity\Book;
use Wesamly\KotobiBundle\Form\Type\BookType;
use Wesamly\KotobiBundle\Entity\Category;

class BooksController extends Controller
{
    public function indexAction()
    {

        $books = $this->getDoctrine()
            ->getRepository('WesamlyKotobiBundle:Book')
            ->findAll();


        return $this->render('WesamlyKotobiBundle:Books:index.html.twig', array(
                'books'=> $books,
            ));
    }

    public function addAction(Request $request)
    {
        $form = $this->createForm(new BookType(), new Book());

        $form->handleRequest($request);
        if ($form->isValid()) {
            $book = $form->getData();
            $category = null;
            if( $form->get('category')->getData() == ''){
                $new_category = $form->get('new_category')->getData();
                $category = new Category();
                $category->setName($new_category);
                $book->setCategory($category);
            }

            $book->setAdded();
            $book->setUpdated();
            $em = $this->getDoctrine()->getManager();
            if($category) $em->persist($category);
            $em->persist($book);
            $em->flush();

            return $this->redirect($this->generateUrl('wesamly_kotobi_bookslist'));
        }


        return $this->render('WesamlyKotobiBundle:Books:edit.html.twig', array(
            'form' => $form->createView()
        ));
    }

    public function editAction($id, Request $request)
    {

        $book = $this->getDoctrine()
            ->getRepository('WesamlyKotobiBundle:Book')
            ->find($id);

        if (!$book) {
            throw $this->createNotFoundException(
                'No book found for id '.$id
            );
        }

        $form = $this->createForm(new BookType(), $book);

        $form->handleRequest($request);
        if ($form->isValid()) {
            $category = null;
            if( $form->get('category')->getData() == ''){
                $new_category = $form->get('new_category')->getData();
                $category = new Category();
                $category->setName($new_category);
                $book->setCategory($category);
            }

            $book->setUpdated();
            $em = $this->getDoctrine()->getManager();
            if($category) $em->persist($category);
            $em->persist($book);
            $em->flush();

            return $this->redirect($this->generateUrl('wesamly_kotobi_bookslist'));
        }
        return $this->render('WesamlyKotobiBundle:Books:edit.html.twig', array(
                'form' => $form->createView(),
            ));
    }

    public function deleteAction($id, Request $request)
    {

        $book = $this->getDoctrine()
            ->getRepository('WesamlyKotobiBundle:Book')
            ->find($id);

        if (!$book) {
            throw $this->createNotFoundException(
                'No book found for id '.$id
            );
        }

        $form = $this->createFormBuilder(array())
            ->add('id', 'hidden', array('data'=>$book->getId()))
            ->add('confirm','submit')
            ->add('cancel','submit')
            ->getForm();

        $form->handleRequest($request);

        if ($form->isValid() && $request->getMethod()=='POST') {

            if($form->get('confirm')->isClicked()) {
                //delete
                $book = $this->getDoctrine()
                    ->getRepository('WesamlyKotobiBundle:Book')
                    ->find($id);
                $em = $this->getDoctrine()->getManager();
                $em->remove($book);
                $em->flush();
            }
            return $this->redirect($this->generateUrl('wesamly_kotobi_bookslist'));

        }
        return $this->render('WesamlyKotobiBundle:Books:delete.html.twig', array(
                'form' => $form->createView(),
                'book' => $book
            ));
    }
}
