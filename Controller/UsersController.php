<?php
namespace MesClics\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use MesClicsBundle\Entity\MesClicsUser as User;
use MesClics\UserBundle\Form\UserAdminRegistrationType;
use MesClics\UserBundle\Form\UserType;
use MesClics\MessagesBundle\Entity\Message;
use MesClics\MessagesBundle\Form\UserMessageType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UsersController extends Controller{
     
    //UTILISATEURS
    /**
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function getUsersAction(Request $request){
        //on récupère la liste des utilisateurs
        $em = $this->getDoctrine()->getManager();
        $usersRepo = $em->getRepository('MesClicsBundle:MesClicsUser');

        //TODO:DEBUG: on crée un formulaire d'ajout d'utilisateur
        // $user = new User();
        // $userForm = $this->createForm(UserAdminRegistrationType::class, $user);
        // $userForm->handleRequest($request);

        // if($userForm->isValid()){
        //     $userManager = $this->get('fos_user.user_manager');
        //     $exists = $userManager->findUserBy(array('email' => $user->getEmail()));

        //     if ($exists instanceof User){
        //         throw new HttpException(409, 'Email déjà enregistré');
        //     }
        //     $userManager->updateUser($user);
        // }

        $users = $usersRepo->getUsersList('username');

        $args = array(
            'currentSection'  => 'utilisateurs',
            'users' => $users,
            // 'addUserForm' => $userForm->createView()
        );

        return $this->render('MesClicsAdminBundle:Panel:users.html.twig', $args);
     }

    //UTILISATEUR - Fiche individuelle
    /**
     * @Security("has_role('ROLE_ADMIN')")
     */
     public function getUserAction($id, Request $request){
         //on récupère l'utilisateur
         $em = $this->getDoctrine()->getManager();
         $userRepo = $em->getRepository('MesClicsUserBundle:User');
         $user = $userRepo->find($id);
        
        //on ajoute un formulaire pour changer les paramètres de l'utilisateur
        $userForm = $this->createForm(UserType::class, $user);
        
        if($request->isMethod('POST')){
            $userForm->handleRequest($request);

            if($userForm->isValid()){
                $userManager = $this->get('fos_user.user_manager');
                $exists = $userManager->findUserBy(array('email' => $user->getEmail()));
                //on empêche la création de plusieurs utilisateurs avec le même mail
                if($exists instanceof User && $user->getId() != $exists->getId()){
                        throw new HttpException(409, 'Email déjà enregistré pour un autre utilisateur');
                }
                $userManager->updateUser($user);
            }
        }

        //on ajoute un formulaire pour l'envoi d'un message sur la console d'administratioin de l'utilisateur
        $message = new Message();
        $message->addRecipient($user);
        $message->setAuthor($this->get('security.token_storage')->getToken()->getUser());
        $messageForm =  $this->createForm(UserMessageType::class, $message);
        if($request->isMethod('POST')){
            $messageFormManager = $this->get('mesclics_messages.form_manager.user');
            if($messageFormManager->handle($messageForm)->hasSucceeded()){
            $args = array('id' => $id);
            return $this->redirectToRoute('mesclics_admin_user', $args);
            }
        }

        //on récupère les messages non lus du client
        $unreadMessages = $em->getRepository('MesClicsMessagesBundle:Message')->getUnreadMessages($user);

        $args = array(
            'currentSection' => 'utilisateurs',
            'user' => $user,
            'updateUserForm' => $userForm->createView(),
            'userMessageForm' => $messageForm->createView(),
            'unreadMessages' => $unreadMessages
        );
        return $this->render('MesClicsAdminBundle:Panel:user.html.twig', $args);
    }
}