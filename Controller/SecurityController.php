<?php
namespace MesClics\UserBundle\Controller;


use MesClicsBundle\Entity\MesClicsUser;
use MesClics\UserBundle\Form\LoginType;
use Doctrine\ORM\EntityManagerInterface;
use MesClics\UserBundle\Form\RegisterType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class SecurityController extends Controller{

    public function loginAction(AuthenticationUtils $authenticationUtils, EntityManagerInterface $em){
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        $form = $this->createForm(LoginType::class, ([
            '_username' => $lastUsername
        ]));

        return $this->render('MesClicsBundle:Pages:index.html.twig', array(
            'popups' => array(
                'login' => array(
                    'template' => 'MesClicsUserBundle:Security:login.html.twig',
                    'options' => array(
                        'remember_me' => true,
                        'error' => $error,
                        'form' => $form->createView()
                    )
                )
            )
        ));

    }

    public function registerAction(Request $request, ObjectManager $manager, UserPasswordEncoderInterface $encoder){
        $user = new MesClicsUser('temp_user');
        
        $form = $this->createForm(RegisterType::class, $user);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $encoded_password = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($encoded_password);
            $manager->persist($user);
            $manager->flush();
        }

        $args = array(
            'popups' => array(
                'register' => array(
                    'template' => 'MesClicsUserBundle:Security:register.html.twig',
                    'options' => array(
                        'form' => $form->createView()
                    )
                )
            )
        );

        // TODO: get the page the visitor come from and redirect to it
        // $route = $request->attributes->get('_route');
        // if($route){
        //     return $this->redirectToRoute($route, $args);
        // }

        return $this->render('MesClicsBundle:Pages:index.html.twig', $args);
    }
    
    public function logoutAction(){
        // TODO: write the logout logic
        return $this->redirectToRoute('mesclics_index');
    }

    //TODO: retrievePasswordAction
}
