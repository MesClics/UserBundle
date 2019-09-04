<?php

namespace MesClics\UserBundle\Events\EventListeners;

use Doctrine\ORM\Event\LifecycleEventArgs;
use MesClics\NavigationBundle\Entity\Chronology;

class UserCreationListener{
    
    public function postPersist(LifecycleEventArgs $args){
        $entity = $args->getObject();
        if(!$entity instanceof User){
            return;
        }

        $em = $args->getObjectManager();
        //SET NUMERO AUTO
        // if(!$entity->hasNumero()){
        //     $numero = $this->clientNumerator->numeroAuto($entity, $em);
        //     $entity->setNumero($numero);
        // }

        // $this->executeTrelloActions("postPersist", array("client" => $entity, "entity_manager" => $em));

        //add a chronology
        $chrono = new Chronology();
        $entity->setChronology($chrono);
    }
}