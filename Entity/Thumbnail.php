<?php

namespace MesClics\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Thumbnail
 *
 * @ORM\Table(name="mesclics_thumbnail")
 * @ORM\Entity(repositoryClass="MesClics\UserBundle\Repository\ThumbnailRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Thumbnail
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="color", type="string", length=25, nullable=true)
     */
    private $color;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="path", type="string", length=255, nullable=true)
     */
    private $path;

    private $temp;

    /**
     * @Assert\File(
     *      maxSize = "200k",
     *      maxSizeMessage= "Veuillez choisir un fichier de moins de 200ko",
     *      mimeTypes = {"image/png", "image/jpg"},
     *      mimeTypesMessage = "Veuillez choisir un fichier de type .jpg ou .png")
     */
    private $file;

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set color
     *
     * @param string $color
     *
     * @return Thumbnail
     */
    public function setColor($color)
    {
        $this->color = $color;

        return $this;
    }

    /**
     * Get color
     *
     * @return string
     */
    public function getColor()
    {
        return $this->color;
    }
    

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Thumbnail
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set path
     *
     * @param string $path
     *
     * @return Thumbnail
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Get path
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Set file
     * 
     * @param UploadedFile $file 
     */
    public function setFile(UploadedFile $file = null){
        $this->file = $file;
        //on vérifie si on a un vieux path
        if(is_file($this->getAbsolutePath())){
            $this->temp = $this->getAbsolutePath();
        } else{
            $this->path = 'initial';
        }
    }

    /**
     * Get file
     * 
     * @return UploadedFile
     */
    public function getFile(){
        return $this->file;
    }

    public function getAbsolutePath(){
        if($this->path){
            return $this->getUploadRootDir().'/'.$this->getPath();
        } else{
            return null;
        }
    }

    public function getWebPath(){
        if($this->path){
            return $this->getUploadDir().'/'.$this->getPath();
        } else{
            return null;
        }
    }

    protected function getUploadRootDir(){
        return __DIR__.'/../../../../web/'.$this->getUploadDir();
    }

    protected function getUploadDir(){
        return 'uploads/thumbnails';
    }
    
    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload(){
        if($this->getFile() !== null){
            //on attribue le nom original du fichier au nom (on le modifiera après persist par l'id du thumbnail)
            $this->name = $this->getFile()->getClientOriginalName();
        }
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload(){
        if($this->getFile() === null){
            return;
        }

        if(isset($this->temp)){
            //on supprime l'ancien fichier
            unlink($this->temp);
            $this->temp = null;
        }
        //on modifie le nom du thumbnail par son id
        $this->name = $this->id;
        //on modifie le nom du path = name.extension
        $this->path = $this->name . '.' . $this->getFile()->guessExtension();

        $this->getFile()->move(
            $this->getUploadRootDir(),
            $this->path
        );

        $this->setFile(null);
    }

    /**
     * @ORM\PreRemove()
     */
    public function storeFilenameForRemove(){
        $this->temp = $this->getAbsolutePath();
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeUpload(){
        if(isset($this->temp)){
            unlink($this->temp);
        }
    }

}
