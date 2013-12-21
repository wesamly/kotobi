<?php
namespace Wesamly\KotobiBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('username', null);

        $builder->add('password', 'repeated', array(
                'first_name'  => 'password',
                'second_name' => 'confirm',
                'type'        => 'password',
                'required' =>false
            ));
        $builder->add('email', 'email');
        $builder->add('isActive', null, array(
                'label'     =>  'Is Active',
                'required'  =>  false
            ));
        $builder->add('save', 'submit');
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
                'data_class' => 'Wesamly\KotobiBundle\Entity\User'
            ));
    }

    public function getName()
    {
        return 'user';
    }
}