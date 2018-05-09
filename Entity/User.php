<?php
namespace MesClics\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;

/**
 * @ORM\Table(name="mesclics_user")
 * @ORM\Entity(repositoryClass="MesClics\UserBundle\Repository\UserRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class User extends BaseUser{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\OneToOne(targetEntity="Thumbnail",  cascade={"persist"})
     */
    protected $thumbnail;

    /**
     * @ORM\ManyToOne(targetEntity="\MesClics\EspaceClientBundle\Entity\Client", cascade={"persist"})
     */
    protected $client;

    /**
     * @ORM\Column(name="newsletter", type="boolean")
     * 
     */
    protected $newsletter;

    /**
     * @ORM\Column(name="banned", type="boolean", nullable=true)
     */
    protected $banned;

    /**
     * @ORM\Column(name="creation_date", type="datetime")
     */
    protected $creationDate;

    /**
     * Set thumbnail
     *
     * @param \MesClics\UserBundle\Entity\Thumbnail $thumbnail
     *
     * @return User
     */
    public function setThumbnail(\MesClics\UserBundle\Entity\Thumbnail $thumbnail = null)
    {
        $this->thumbnail = $thumbnail;
        return $this;
    }

    /**
     * Get thumbnail
     *
     * @return \MesClics\UserBundle\Entity\Thumbnail
     */
    public function getThumbnail()
    {
        return $this->thumbnail;
    }

    /**
     * Set client
     *
     * @param \MesClics\EspaceClientBundle\Entity\Client $client
     *
     * @return User
     */
    public function setClient(\MesClics\EspaceClientBundle\Entity\Client $client = null)
    {
        $this->client = $client;

        return $this;
    }

    /**
     * Get client
     *
     * @return \MesClics\EspaceClientBundle\Entity\Client
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * Set newsletter
     *
     * @param boolean $newsletter
     *
     * @return User
     */
    public function setNewsletter($newsletter)
    {
        $this->newsletter = $newsletter;

        return $this;
    }

    /**
     * Get newsletter
     *
     * @return boolean
     */
    public function hasNewsletter()
    {
        return $this->newsletter;
    }

    /**
     * Get newsletter
     *
     * @return boolean
     */
    public function getNewsletter()
    {
        return $this->newsletter;
    }

    /**
     * Set banned
     *
     * @param boolean $banned
     *
     * @return User
     */
    public function setBanned($banned)
    {
        $this->banned = $banned;

        return $this;
    }

    /**
     * Get banned
     *
     * @return boolean
     */
    public function getBanned()
    {
        return $this->banned;
    }


    /**
     * Set creationDate
     *
     * @param \DateTime $creationDate
     *
     * @return User
     */
    public function setCreationDate($creationDate)
    {
        $this->creationDate = $creationDate;

        return $this;
    }

    /**
     * Get creationDate
     *
     * @return \DateTime
     */
    public function getCreationDate()
    {
        return $this->creationDate;
    }


    /**
     * @ORM\PrePersist
     */
    public function creationDate(){
        $this->setCreationDate(new \DateTime());
    }


    public function _construct($roles = array('ROLE_USER'), $thumbnail = NULL, $client = NULL){
        parent::construct();

        foreach($roles AS $role){
            $this->addRole($role);
        }
        $this->setThumbnail($thumbnail);
        $this->setClient($client);
    }

    public function labelRecepient(){
        if($this->client){
            return $this->username . ' (' . $this->client->getNom() .')';
        }

        return $this->username;
    }
}
