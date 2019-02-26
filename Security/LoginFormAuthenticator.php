<?php

namespace MesClics\UserBundle\Security;

use MesClics\UserBundle\Form\LoginType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Util\TargetPathTrait;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;

class LoginFormAuthenticator extends AbstractFormLoginAuthenticator{
    use TargetPathTrait;

    private $form_factory;
    private $em;
    private $router;
    private $csrf_token_manager;
    private $password_encoder;

    public function __construct(FormFactoryInterface $form_factory, EntityManagerInterface $em, RouterInterface $router, CsrfTokenManagerInterface $csrf_token_manager, UserPasswordEncoderInterface $password_encoder){
        $this->form_factory = $form_factory;
        $this->em = $em;
        $this->router = $router;
        $this->csrf_token_manager = $csrf_token_manager;
        $this->password_encoder = $password_encoder;
    }
    
    public function supports(Request $request){
        // return $request->headers->has('X-AUTH-TOKEN');
        
        $isLoginSubmit = $request->attributes->get('_route') === 'mesclics_security_login' && $request->isMethod("POST");

        if($isLoginSubmit){
            return true;
        }

        return false;
    }

    public function getCredentials(Request $request){
        $login = $request->request->get('login');
        $credentials = [
            'username' => $login['_username'],
            'password' => $login['_password'],
            'csrf_token' => $login['_token']
        ];
        return $credentials;
    }

    /**
     * @return User (which class would be the user provider's one)
     */
    public function getUser($credentials, UserProviderInterface $userProvider){
        //check the csrf token
        $token = new CsrfToken('login', $credentials['csrf_token']);
        if(!$this->csrf_token_manager->isTokenValid($token)){
            throw new InvalidCsrfTokenException();
        }

        $username = $credentials['username'];

        //so users can log with their username or their email, we call our custom repository method (loadUserFromUsernameOrEmail)
        $user_entity = 'MesClicsBundle\Entity\MesClicsUser';
        $users_repo = $this->em->getRepository($user_entity);
        $user = $users_repo->loadUserByUsernameOrEmail($username);

        return $user;
    }

    /**
     * @return bool
     * form error : "Invalid Credentials"
     */
    public function checkCredentials($credentials, UserInterface $user){
        $isPasswordValid = $this->password_encoder->isPasswordValid($user, $credentials['password']);
        // dump($password); die();

        return $isPasswordValid;
    }

    protected function getLoginUrl(){
        return $this->router->generate('mesclics_security_login');
    }

    //in case visitor did not came to the login page from another page, redirect to homepage
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey){
        //redirect to the original page if it was not directly the login page
        if($targetPath = $this->getTargetPAth($request->getSession(), $providerKey)){
            return new RedirectResponse($targetPath);
        }
        //else redirect to home page
        return new RedirectResponse($this->router->generate('mesclics_index'));
    }
}
