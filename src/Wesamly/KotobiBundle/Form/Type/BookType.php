<?php
namespace Wesamly\KotobiBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class BookType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title', null)
            ->add('intro', null)
            ->add('published', null)
            ->add('type', 'choice', array(
                    'choices' => array('Hardcover'=>'Hardcover','Paperback'=>'Paperback','E-Book'=>'E-Book'),
                    'empty_value' => 'Choose a Type',
                ))
            ->add('category', 'entity', array(
                    'class' => 'WesamlyKotobiBundle:Category',
                    'property' => 'name',
                    'empty_value' => 'Choose a Category',
                    'required' =>false
                ))
            ->add('new_category','text', array(
                    'mapped'=>false,
                    'required' =>false
                ))
            ->add('tags', 'entity', array(
                    'class' => 'WesamlyKotobiBundle:Tag',
                    'property' => 'name',
                    'multiple' => true,
                    'expanded' => true
                ))
            ->add('new_tags','text', array(
                    'mapped'=>false,
                    'required' =>false
                ))
            ->add('save', 'submit');
    }

    public function getName()
    {
        return 'book';
    }
}