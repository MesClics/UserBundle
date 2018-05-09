<?php
namespace MesClics\UserBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use MesClics\UserBundle\Entity\User;
 
class LoadUser implements FixtureInterface
{
 
  /**
  * Load data fixtures with the passed EntityManager
  * @param ObjectManager $manager
  */
  public function load(ObjectManager $manager)
  {
      $user = new User();
      $user->setUsername('admin');
      $user->setSalt('');
      $user->setRoles(array('ROLE_ADMIN'));
      $user->setEmail('admin@mail.fr');
      $user->setNewsletter(true);
      $user->setEnabled(true);
      $user->setPlainPassword('adminpass');
      $manager->persist($user); 
      $manager->flush();
  }
  
}