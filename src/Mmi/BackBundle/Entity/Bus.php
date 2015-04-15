<?php

namespace Mmi\BackBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Bus
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Mmi\BackBundle\Entity\BusRepository")
 */
class Bus
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
     * @ORM\Column(name="bus_desti", type="string", length=150)
     */
    private $busDesti;

    /**
     * @var \Time
     *
     * @ORM\Column(name="bus_heure", type="time")
     */
    private $busHeure;

    /**
     * @var string
     *
     * @ORM\Column(name="bus_num", type="string")
     */
    private $busNum;

    /**
     * @ORM\ManyToMany(targetEntity="Mmi\BackBundle\Entity\User",inversedBy="bus")
     *
     */
    private $user;


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
     * Set busDesti
     *
     * @param string $busDesti
     * @return Bus
     */
    public function setBusDesti($busDesti)
    {
        $this->busDesti = $busDesti;

        return $this;
    }

    /**
     * Get busDesti
     *
     * @return string 
     */
    public function getBusDesti()
    {
        return $this->busDesti;
    }

    /**
     * Set busHeure
     *
     * @param \DateTime $busHeure
     * @return Bus
     */
    public function setBusHeure($busHeure)
    {
        $this->busHeure = $busHeure;

        return $this;
    }

    /**
     * Get busHeure
     *
     * @return \DateTime 
     */
    public function getBusHeure()
    {
        return $this->busHeure;
    }

    /**
     * Set busNum
     *
     * @param integer $busNum
     * @return Bus
     */
    public function setBusNum($busNum)
    {
        $this->busNum = $busNum;

        return $this;
    }

    /**
     * Get busNum
     *
     * @return integer 
     */
    public function getBusNum()
    {
        return $this->busNum;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->utilisateur = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add utilisateur
     *
     * @param \Mmi\BackBundle\Entity\Utilisateur $utilisateur
     * @return Bus
     */
    public function addUtilisateur(\Mmi\BackBundle\Entity\Utilisateur $utilisateur)
    {
        $this->utilisateur[] = $utilisateur;

        return $this;
    }

    /**
     * Remove utilisateur
     *
     * @param \Mmi\BackBundle\Entity\Utilisateur $utilisateur
     */
    public function removeUtilisateur(\Mmi\BackBundle\Entity\Utilisateur $utilisateur)
    {
        $this->utilisateur->removeElement($utilisateur);
    }

    /**
     * Get utilisateur
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getUtilisateur()
    {
        return $this->utilisateur;
    }

    /**
     * Add user
     *
     * @param \Mmi\BackBundle\Entity\User $user
     * @return Bus
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
}
