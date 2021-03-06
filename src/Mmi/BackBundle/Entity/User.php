<?php
// src/Acme/UserBundle/Entity/User.php

namespace Mmi\BackBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(name="nom", type="string", length=255)
     *
     * @Assert\NotBlank(message="Entrez votre nom.", groups={"Registration", "Profile"})
     * @Assert\Length(
     *     min=3,
     *     max="255",
     *     minMessage="Le nom est trop court.",
     *     maxMessage="Le nom est trop grand.",
     *     groups={"Registration", "Profile"}
     * )
     */
    private $nom;

    /**
     * @ORM\Column(name="prenom", type="string", length=255)
     *
     */
    private $prenom;

    /**
     * @ORM\Column(name="date", type="date")
     *
     */
    private $date;

    /**
     * @ORM\Column(name="semaine", type="date")
     *
     */
    private $semaine;

    /**
     * @ORM\ManyToMany(targetEntity="Mmi\BackBundle\Entity\Message",mappedBy="user",cascade={"persist"})
     */
    private $message;

    /**
     * @ORM\ManyToMany(targetEntity="Mmi\BackBundle\Entity\Bus",mappedBy="user",cascade={"persist"})
     */
    private $bus;

    /**
     * @ORM\OneToMany(targetEntity="Mmi\BackBundle\Entity\Playlist", mappedBy="user", cascade={"persist", "remove", "merge"})
     */
    protected $playlist;

    /**
     * @ORM\OneToMany(targetEntity="Mmi\BackBundle\Entity\Video", mappedBy="user")
     */
    protected $video;




    public function __construct()
    {
        parent::__construct();
        $date = new \DateTime();
        $date->add(new \DateInterval('P14D'));
        $this->expiresAt = $date;
        $this->date= new \DateTime();
        $this->roles = array('ROLE_CLIENT');
        $this->video = new ArrayCollection();
        // your own logic
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set nom
     *
     * @param string $nom
     * @return User
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string 
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set prenom
     *
     * @param string $prenom
     * @return User
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;

        return $this;
    }

    /**
     * Get prenom
     *
     * @return string 
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * Set statut
     *
     * @param string $statut
     * @return User
     */
    public function setStatut($statut)
    {
        $this->statut = $statut;

        return $this;
    }

    /**
     * Get statut
     *
     * @return string 
     */
    public function getStatut()
    {
        return $this->statut;
    }

    /**
     * Set date
     *
     * @param \Datetime $date
     * @return User
     */
    public function setDate(\Datetime $date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \Datetime 
     */
    public function getDate()
    {
        return $this->date;
    }


    /**
     * Add message
     *
     * @param \Mmi\BackBundle\Entity\Message $message
     * @return User
     */
    public function addMessage(\Mmi\BackBundle\Entity\Message $message)
    {
        $this->message[] = $message;

        return $this;
    }

    /**
     * Remove message
     *
     * @param \Mmi\BackBundle\Entity\Message $message
     */
    public function removeMessage(\Mmi\BackBundle\Entity\Message $message)
    {
        $this->message->removeElement($message);
    }

    /**
     * Get message
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Add bus
     *
     * @param \Mmi\BackBundle\Entity\Bus $bus
     * @return User
     */
    public function addBus(\Mmi\BackBundle\Entity\Bus $bus)
    {
        $this->bus[] = $bus;

        return $this;
    }

    /**
     * Remove bus
     *
     * @param \Mmi\BackBundle\Entity\Bus $bus
     */
    public function removeBus(\Mmi\BackBundle\Entity\Bus $bus)
    {
        $this->bus->removeElement($bus);
    }

    /**
     * Get bus
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getBus()
    {
        return $this->bus;
    }
    

    /**
     * Set playlist
     *
     * @param \Mmi\BackBundle\Entity\Playlist $playlist
     * @return User
     */
    public function setPlaylist(\Mmi\BackBundle\Entity\Playlist $playlist = null)
    {
        $this->playlist = $playlist;

        return $this;
    }

    /**
     * Get playlist
     *
     * @return \Mmi\BackBundle\Entity\Playlist 
     */
    public function getPlaylist()
    {
        return $this->playlist;
    }


    /**
     * Add playlist
     *
     * @param \Mmi\BackBundle\Entity\Playlist $playlist
     * @return User
     */
    public function addPlaylist(\Mmi\BackBundle\Entity\Playlist $playlist)
    {
        $this->playlist[] = $playlist;

        return $this;
    }

    /**
     * Remove playlist
     *
     * @param \Mmi\BackBundle\Entity\Playlist $playlist
     */
    public function removePlaylist(\Mmi\BackBundle\Entity\Playlist $playlist)
    {
        $this->playlist->removeElement($playlist);
    }


    /**
     * Add video
     *
     * @param \Mmi\BackBundle\Entity\Video $video
     * @return User
     */
    public function addVideo(\Mmi\BackBundle\Entity\Video $video)
    {
        $this->video[] = $video;

        return $this;
    }

    /**
     * Remove video
     *
     * @param \Mmi\BackBundle\Entity\Video $video
     */
    public function removeVideo(\Mmi\BackBundle\Entity\Video $video)
    {
        $this->video->removeElement($video);
    }

    /**
     * Get video
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getVideo()
    {
        return $this->video;
    }

    /**
     * Get expiresAt
     *
     * @return \DateTime
     */
    public function getExpiresAt()
    {
        return $this->expiresAt;
    }



    /**
     * Set semaine
     *
     * @param \DateTime $semaine
     * @return User
     */
    public function setSemaine($semaine)
    {
        $this->semaine = $semaine;

        return $this;
    }

    /**
     * Get semaine
     *
     * @return \DateTime 
     */
    public function getSemaine()
    {
        return $this->semaine;
    }
}
