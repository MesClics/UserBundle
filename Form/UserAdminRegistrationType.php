<?php

namespace MesClics\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use MesClics\EspaceClientBundle\Repository\ClientRepository;
use MesClics\UserBundle\Entity\Thumbnail;
use MesClics\UserBundle\Form\UserType;

class UserAdminRegistrationType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('client', EntityType::class, array(
            'label' => 'associer le nouvel utilisateur à un compte client',
            'required' => false,
            'class' => 'MesClicsEspaceClientBundle:Client',
            'choice_label' => 'getNom',
            'multiple' => false,
            'query_builder' => function(ClientRepository $client_repo){
                    return $client_repo->getClientsList(true);
                }
            ))
        ->add('username', TextType::class, array(
            'label' => 'nom d\'utilisateur'
        ))
        ->add('plainPassword', RepeatedType::class, array(
            'type' => PasswordType::class,
            'invalid_message' => 'Les deux mots de passes doivent être identiques',
            'first_options' => array(
                'label' => 'mot de passe'
            ),
            'second_options' => array(
                'label' => 'répétez le mot de passe'
            )
        ))
        ->add('email', EmailType::class, array(
            'label' => 'adresse mail'
        ))
        ->add('thumbnail', ThumbnailType::class, array(
            'required' => false
        ))
        ->add('newsletter', CheckboxType::class, array(
            'label' => 'abonner à la newsletter',
            'required' => false
        ))
        ->add('submit', SubmitType::class, array(
            'label' => 'Enregistrer' 
        ))
        ;
    }
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'MesClics\UserBundle\Entity\User'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'mesclics_userbundle_user';
    }

    // public function getParent(){
    //     return 'fos_user_registration';
    // }

    public function getName(){
        return 'mesclics_user_admin_registration';
    }


}
