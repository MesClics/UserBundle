<?php

namespace MesClics\UserBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;

class UserRepository extends EntityRepository implements UserLoaderInterface{
    public function loadUserByUsername($username){
        $qb = $this
        ->createQueryBuilder('u')
        ->where('u.username = :username OR u.email = :email')
        ->setParameter('username', $username)
        ->setParameter('email', $username);

        return $qb->getQuery()->getOneOrNullResult();
    }

    public function loadUserByUsernameOrEmail($username){
        $qb = $this
        ->createQueryBuilder('u')
        ->where('u.username = :username OR u.email = :email')
        ->setParameter('username', $username)
        ->setParameter('email', $username);

        return $qb->getQuery()->getOneOrNullResult();
    }
}
