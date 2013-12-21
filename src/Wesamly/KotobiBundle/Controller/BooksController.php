<?php

namespace Wesamly\KotobiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\DateTime;
use Wesamly\KotobiBundle\Entity\Book;
use Wesamly\KotobiBundle\Entity\Tag;
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

            //Do we have new category added
            $category = $this->getNewCategory($form);
            if($category != null){
                $book->setCategory($category);
            }
            //Do we have new tags added
            $tags = $this->getNewTags($form);
            if(!empty($tags)){
                foreach($tags as $tag){
                    $book->addTag($tag);
                }
            }

            $book->setAdded();
            $book->setUpdated();

            $em = $this->getDoctrine()->getManager();
            if($category) $em->persist($category);
            if(!empty($tags)){
                foreach($tags as $tag){
                    $em->persist($tag);
                }
            }
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
            //Do we have new category added
            $category = $this->getNewCategory($form);
            if($category != null){
                $book->setCategory($category);
            }
            //Do we have new tags added
            $tags = $this->getNewTags($form);
            if(!empty($tags)){
                foreach($tags as $tag){
                    $book->addTag($tag);
                }
            }

            $book->setUpdated();
            $em = $this->getDoctrine()->getManager();
            if($category) $em->persist($category);
            if(!empty($tags)){
                foreach($tags as $tag){
                    $em->persist($tag);
                }
            }
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

    /**
     * Check form data for new added category, and add it as a Category object
     *
     * @param $form
     * @return null|Category
     */
    private function getNewCategory($form){
        $category = null;
        if( $form->get('category')->getData() == ''){
            $new_category = $form->get('new_category')->getData();
            $category = new Category();
            $category->setName($new_category);

        }
        return $category;
    }

    /**
     * Check form for new added tags, and add them as Tag objects
     *
     * @param $form
     * @return array|null
     */
    private function getNewTags($form){
        $tags = null;
        if( $form->get('new_tags')->getData() != ''){
            $new_tags = explode(',', $form->get('new_tags')->getData());
            foreach($new_tags as $new_tag){
                $tag = new Tag();
                $tag->setName( trim( $new_tag));
                $tags[] = $tag;

            }
        }
        return $tags;
    }

}
