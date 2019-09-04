<?php

namespace MesClics\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use MesClics\UserBundle\Entity\Thumbnail;
use MesClics\NavigationBundle\Entity\Chronology;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\MappedSuperclass
 * @ORM\HasLifecycleCallbacks()
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
     * @ORM\Column(type="json", nullable=true)
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

    /**
     * @ORM\OneToOne(targetEntity="MesClics\NavigationBundle\Entity\Chronology", cascade={"persist"})
     */
    protected $chronology;

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
        $roles = json_decode($this->roles);
        if(empty($roles)){
            $roles[] = 'ROLE_USER';
        }
        return $roles;
    }

    public function addRole(string $role){
        $roles = json_decode($this->roles);
        $roles[] = $role;
        $this->roles = json_encode($roles);
        return $this;
    }

    public function removeRole(string $role){
        $roles = json_decode($this->roles);
        $roles[$role] = null;
        $this->roles = json_encode($roles);
        return$this;
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

    public function setChronology(Chronology $chronology){
        $this->chronology = $chronology;

        return $this;
    }

    public function getChronology(){
        return $this->chronology;
    }

    /**
     * @ORM\PrePersist
     */
    public function creationDate(){
        $this->setCreationDate(new \DateTime());
    }
}