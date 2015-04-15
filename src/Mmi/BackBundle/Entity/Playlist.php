<?php

namespace Mmi\BackBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Playlist
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Mmi\BackBundle\Entity\PlaylistRepository")
 */
class Playlist
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="pl_nom", type="string", length=150)
     */
    private $plNom;

    /**
     * @var \integer
     *
     * @ORM\Column(name="pl_duree", type="integer")
     */
    private $plDuree;


    /**
    * @ORM\ManyToOne(targetEntity="Mmi\BackBundle\Entity\User", inversedBy="playlist")
    * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     *
     * @ORM\OneToOne(targetEntity="Mmi\BackBundle\Entity\Creneau",cascade={"persist"})
     */
    private $creneau;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="pl_date", type="date")
     */

    private $date;



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
     * Set plNom
     *
     * @param string $plNom
     * @return Playlist
     */
    public function setPlNom($plNom)
    {
        $this->plNom = $plNom;

        return $this;
    }

    /**
     * Get plNom
     *
     * @return string 
     */
    public function getPlNom()
    {
        return $this->plNom;
    }

    /**
     * Set plDuree
     *
     * @param \DateTime $plDuree
     * @return Playlist
     */
    public function setPlDuree($plDuree)
    {
        $this->plDuree = $plDuree;

        return $this;
    }

    /**
     * Get plDuree
     *
     * @return \DateTime 
     */
    public function getPlDuree()
    {
        return $this->plDuree;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->user = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add user
     *
     * @param \Mmi\BackBundle\Entity\User $user
     * @return Playlist
     */
    public function addUser(\Mmi\BackBundle\Entity\User $user)
    {
        $this->user[] = $user;

        return $this;
    }

    /**
     * Remove user
     *
     * @param \Mmi\BackBundle\Entity\User $user
     */
    public function removeUser(\Mmi\BackBundle\Entity\User $user)
    {
        $this->user->removeElement($user);
    }

    /**
     * Get user
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set user
     *
     * @param \Mmi\BackBundle\Entity\User $user
     * @return Playlist
     */
    public function setUser(\Mmi\BackBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }



    /**
     * Set creneau
     *
     * @param \Mmi\BackBundle\Entity\Creneau $creneau
     * @return Playlist
     */
    public function setCreneau(\Mmi\BackBundle\Entity\Creneau $creneau = null)
    {
        $this->creneau = $creneau;

        return $this;
    }

    /**
     * Get creneau
     *
     * @return \Mmi\BackBundle\Entity\Creneau 
     */
    public function getCreneau()
    {
        return $this->creneau;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     * @return Playlist
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime 
     */
    public function getDate()
    {
        return $this->date;
    }
}
