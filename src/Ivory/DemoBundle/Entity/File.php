<?php

namespace Ivory\DemoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * File
 *
 * @author GeLo <geloen.eric@gmail.com>
 * 
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class File 
{
    /**
     * @var integer File ID
     * 
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    public $id;

    /**
     * @var string File name
     * 
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     */
    public $name;

    /**
     * @var string File path
     * 
     * @ORM\Column(type="string", length=255)
     */
    public $path;
    
    /**
     * @var Symfony\Component\HttpFoundation\File\UploadedFile Uploaded file
     * 
     * @Assert\File(maxSize="2M")
     */
    protected $file;
    
    /**
     * Gets the file path
     *
     * @return string File path
     */
    public function getPath()
    {
        return $this->path;
    }
    
    /**
     * Sets the file path
     *
     * @param string $path 
     */
    public function setPath($path)
    {
        $this->path = $path;
    }
    
    /**
     * Gets the uploaded file
     *
     * @return Symfony\Component\HttpFoundation\File\UploadedFile Uploaded file
     */
    public function getFile()
    {
        return $this->file;
    }
    
    /**
     * Sets the uploaded file
     *
     * @param Symfony\Component\HttpFoundation\File\UploadedFile $file 
     */
    public function setFile(UploadedFile $file)
    {
        $this->file = $file;
    }
    
    /**
     * Gets the absolute file path
     *
     * @return string Absolute file path
     */
    public function getAbsolutePath()
    {
        return $this->path === null ? null : $this->getUploadRootDir().DIRECTORY_SEPARATOR.$this->path;
    }
    
    /**
     * Gets the upload root directory
     *
     * @return string Upload root directory
     */
    protected function getUploadRootDir()
    {
        return __DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'web'.DIRECTORY_SEPARATOR.$this->getUploadDir();
    }

    /**
     * Gets the upload directory
     *
     * @return string Upload directory
     */
    protected function getUploadDir()
    {
        return 'uploads';
    }
    
    /**
     * Generates file name
     * 
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload()
    {
        if($this->file !== null)
            $this->setPath(uniqid().'.'.$this->file->guessExtension());
    }

    /**
     * Moves file to their final location
     * 
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload()
    {
        if($this->file === null)
            return;

        $this->file->move($this->getUploadRootDir(), $this->path);

        unset($this->file);
    }

    /**
     * Removes file
     * 
     * @ORM\PostRemove()
     */
    public function removeUpload()
    {
        if(($file = $this->getAbsolutePath()) !== null)
            unlink($file);
    }
}
