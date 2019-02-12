<?php

namespace MesClics\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use MesClics\UserBundle\Entity\Thumbnail;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\MappedSuperclass
 */
abstract class User implements UserInterface, \Serializable{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", unique=true)
     */
    protected $username;

    /**
     * @ORM\Column(type="string", unique=true)
     */
    protected $email;

    /**
     * @ORM\Column(type="json")
     */
    protected $roles;

    /**
     * @ORM\Column(type="string")
     */
    protected $password;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $isActive;

    /**
     * @ORM\OneToOne(targetEntity="MesClics\UserBundle\Entity\Thumbnail",  cascade={"persist"})
     */
    protected $thumbnail;

    /**
     * @ORM\Column(name="creation_date", type="datetime", nullable=true)
     */
    protected $creationDate;

    /**
     * @ORM\Column(name="last_visit", type="datetime", nullable=true)
     * TODO: add EventListener on login
     */
    protected $lastVisit;

    public function __construct(){
        $this->isActive = true;
    }

    public function getId(){
        return $this->id;
    }

    public function getUsername(){
        return $this->username;
    }

    public function setUsername(string $username){
        $this->username = $username;
        return $this;
    }

    public function getEmail(){
        return $this->email;
    }

    public function setEmail(string $email){
        $this->email = $email;
        return $this;
    }

    public function getRoles(){
        return json_decode($this->roles);
    }

    public function addRole(string $role){
        $roles = json_decode($this->getRoles());
        $roles[] = $role;
        $this->roles = json_encode($roles);
        return $this;
    }

    public function removeRole(string $role){
        $this->roles[$role] = null;
    }
    
    public function getPassword(){
        return $this->password;
    }
    

    public function setPassword(string $password){
        $this->password = $password;
        return $this;
    }

    public function serialize(){
        return serialize([
            $this->id,
            $this->username,
            $this->password
        ]);
    }

    public function unserialize($serialized){
        list(
            $this->id,
            $this->username,
            $this->password
        ) = unserialize($serialized, ['allow_classes' => false]);
    }

    public function getSalt(){

    }

    public function eraseCredentials(){
    }

    public function setThumbnail(Thumbnail $thumbnail = null)
    {
        $this->thumbnail = $thumbnail;
        return $this;
    }

    public function getThumbnail()
    {
        return $this->thumbnail;
    }

    public function setCreationDate($creationDate)
    {
        $this->creationDate = $creationDate;

        return $this;
    }

    public function getCreationDate()
    {
        return $this->creationDate;
    }

    public function setLastVisit($lastVisit)
    {
        $this->lastVisit = $lastVisit;

        return $this;
    }

    public function getLastVisit()
    {
        return $this->lastVisit;
    }

    /**
     * @ORM\PrePersist
     */
    public function creationDate(){
        $this->setCreationDate(new \DateTime());
    }
}