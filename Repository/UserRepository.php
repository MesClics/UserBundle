<?php

namespace MesClics\UserBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;

class UserRepository extends EntityRepository implements UserLoaderInterface{
    public function loadUserByUsername($username){
        $user = $this->loadUserByUsernameOrEmail($username);
        if(!$user){
            throw new UsernameNotFoundException('No user found for username or email '. $username);
        }
        return $user;
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
