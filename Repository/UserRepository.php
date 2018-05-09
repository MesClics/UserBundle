<?php

namespace MesClics\UserBundle\Repository;

use MesClics\EspaceClientBundle\Entity\Client;

class UserRepository extends \Doctrine\ORM\EntityRepository
{
    public function getUsersList($orderBy){
        $qb = $this
        ->createQueryBuilder('user')
        ->orderBy('user.username');

        return $qb->getQuery()->getResult();
    }

    public function getUsersListOrderedByClientsQB(){
        $qb = $this
        ->createQueryBuilder('user')
        ->leftJoin('user.client', 'client')
        ->orderBy('client.nom')
        ->addSelect('client');

        return $qb;
    }

    public function getUsersListOfClientQB(Client $client){
        $qb = $this
        ->createQueryBuilder('user')
        ->leftJoin('user.client', 'client')
        ->andWhere('client', ':client')
            ->setParameter(':client', $client)
        ->orderBy('user.username');

        return $qb;
    }
}