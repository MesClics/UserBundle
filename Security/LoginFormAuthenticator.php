<?php

namespace MesClics\UserBundle\Security;

use MesClics\UserBundle\Form\LoginType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;

class LoginFormAuthenticator extends AbstractFormLoginAuthenticator{

    private $form_factory;
    private $em;
    private $router;

    public function __construct(FormFactoryInterface $form_factory, EntityManagerInterface $em, RouterInterface $router){
        $this->form_factory = $form_factory;
        $this->em = $em;
        $this->router = $router;
    }
    
    public function supports(Request $request){
        $isLoginSubmit = $request->attributes->get('_route') === 'mesclics_security_login' && $request->isMethod("POST");

        if(!$isLoginSubmit){
            return;
        }

        //handle the login form
        $form = $this->form_factory->create(LoginType::class);
        $form->handleRequest($request);

        //get the form datas
        $data = $form->getData();

        return $data;
    }

    public function getCredentials(Request $request){
        return ['token' => $request->headers->get('X-AUTH-TOKEN')];
    }

    public function getUser($credentials, UserProviderInterface $userProvider){
        $username = $credentials['_username'];

        //if users can log with their email as username
        //TODO: replace email by a parameter so we can config how to authenticate (by email, by name, by token etc)
        $users_repo = $this->em->getRepository('MesClicsUserBundle:User');
        return $users_repo->findOneBy(["email" => $username]);
    }

    public function checkCredentials($credentials, UserInterface $user){
        $password = $credentials['_password'];

        // TODO: handle passwords
        if($password === $user->getPassword() ){
            return true;
        }

        return false;
    }

    protected function getLoginUrl(){
        return $this->router->generate('mesclics_security_login');
    }

    //in case visitor did not came to the login page from another page, redirect to homepage
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey){
       $response = new RedirectResponse($this->router->generate('mes_clics'));
       return $response;
    }
}
