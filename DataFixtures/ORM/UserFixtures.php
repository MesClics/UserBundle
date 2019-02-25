<?php
namespace MesClics\UserBundle\DataFixtures\ORM;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use MesClicsBundle\Entity\MesClicsUser as User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture{
    public const ADMIN_USER_REFERENCE = "admin-user";

    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder){
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager){
        $userAdmin = new User('admin');
        // password
        $encoded_password = $this->encoder->encodePassword($userAdmin, 'adminpass');
        $userAdmin
        ->setUsername('admin')
        ->addRole('ROLE_ADMIN')
        ->setPassword($encoded_password)
        ->setEmail("admin@mail.fr")
        ->setNewsletter(false);
        
        $manager->persist($userAdmin);
        $manager->flush();

        $this->addReference(self::ADMIN_USER_REFERENCE, $userAdmin);
    }
}