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
        // $form->handleRequest($request);
        return $this->render('MesClicsUserBundle:Security:login.html.twig', [
            'error'         => $error,
            'form'          => $form->createView()
        ]);

    }

    public function registerAction(Request $request, ObjectManager $manager, UserPasswordEncoderInterface $encoder){
        $user = new MesClicsUser('temp_user');
        //by default the user has ROLE_USER
        $user->addRole("ROLE_USER");
        
        $form = $this->createForm(RegisterType::class, $user);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $encoded_password = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($encoded_password);
            $manager->persist($user);
            $manager->flush();
        }

        $args = array(
            'form' => $form->createView()
        );

        return $this->render('MesClicsUserBundle:Security:register.html.twig', $args);
    }
    
    public function logoutAction(){
        // TODO: write the logout logic
        return $this->redirectToRoute('mesclics_index');
    }
}
