<?php

namespace MesClics\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class RegisterType extends AbstractType{

    public function buildForm(FormBuilderInterface $builder, array $options){
        $builder
        ->add('_username', TextType::class)
        ->add('_email', TextType::class)
        ->add('_password', RepeatedType::class, array(
            'type' => PasswordType::class,
            'first_name' => '_password1',
            'second_name' => '_password2',
            'invalid_message' => 'passwords do not match'
        ));
    }
}