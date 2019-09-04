<?php

namespace MesClics\UserBundle\Events\EventListeners;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\Event;
use MesClics\NavigationBundle\Entity\Chronology;
use MesClics\NavigationBundle\Navigator\Navigator;

class UserInteractiveLoginListener{
    private $em;
    // private $navigator;

    // TODO: cancel $navigator param
    public function __construct(EntityManagerInterface $em, Navigator $navigator){
        $this->em = $em;
        // $this->navigator = $navigator;
    }

    public function proceed(Event $event){
        //TODO: can be cancelled when in prod (DEV_FIXME: cf method's comment)
        $this->initializeChronology($event);
        // // Update navigator
        // $this->navigator->setUser($event->getAuthenticationToken()->getUser());
    }

    //DEV_FIXME: add a chronology to users created before the creation of the NavigationBundle;
    private function initializeChronology(Event $event){
        //check if user has Chronology and create one if necessary
        $user = $event->getAuthenticationToken()->getUser();

        if(!$user->getChronology()){
            $chrono = new Chronology();
            $this->em->persist($chrono);
            $user->setChronology($chrono);
            $this->em->flush();
        }
    }

    //TODO:
    private function getLastActions(Event $event){
    }
}