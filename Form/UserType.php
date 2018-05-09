<?php

namespace MesClics\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
//ENITIES & REPOSITORIES
use MesClics\EspaceClientBundle\Repository\ClientRepository;
//FORMS
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use MesClics\MessagesBundle\Form\UserMessageType;

class UserType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('username', TextType::class, array(
            'label' => 'pseudo'
        ))
        ->add('email', EmailType::class, array(
            'label' => 'email',
        ))
        ->add('banned', ButtonType::class, array(
            'label' => 'bannir'
        ))
        ->add('client', EntityType::class, array(
            'label' => 'associer l\'utilisateur à un compte client',
            'required' => false,
            'class' => 'MesClicsEspaceClientBundle:Client',
            'choice_label' => 'getNom',
            'multiple' => false,
            'query_builder' => function(ClientRepository $client_repo){
                    return $client_repo->getClientsList(true);
                }
            ))
        // ->add('messagesTo', UserMessageType::class, array(
        //     'data_class' => null,
        //     'label' => 'Laisser un message à l\'utilisateur sur sa console'
        // ))
        ->add('submit', SubmitType::class, array(
            'label' => 'enregistrer les modifications'
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


}
