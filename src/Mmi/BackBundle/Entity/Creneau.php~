<?php

namespace Mmi\BackBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Creneau
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Mmi\BackBundle\Entity\CreneauRepository")
 */
class Creneau
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
     * @ORM\Column(name="cr_nom", type="string", length=255)
     */
    private $crNom;

    /**
     * @var string
     *
     * @ORM\Column(name="cr_desc", type="string", length=255)
     */
    private $crDesc;

    /**
     * @var string
     *
     * @ORM\Column(name="cr_jour", type="string", length=255)
     */
    private $crJour;

    /**
     * 
     *
     * @ORM\Column(name="cr_heure", type="time")
     */
    private $crHeure;


    /**
     * @var integer
     *
     * @ORM\Column(name="cr_duree", type="integer")
     */
    private $crDuree;

    /**
     * @var string
     *
     * @ORM\Column(name="cr_cat", type="string", length=255)
     */

    private $categorie;




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
     * Set crNom
     *
     * @param string $crNom
     * @return Creneau
     */
    public function setCrNom($crNom)
    {
        $this->crNom = $crNom;

        return $this;
    }

    /**
     * Get crNom
     *
     * @return string 
     */
    public function getCrNom()
    {
        return $this->crNom;
    }

    /**
     * Set crDesc
     *
     * @param string $crDesc
     * @return Creneau
     */
    public function setCrDesc($crDesc)
    {
        $this->crDesc = $crDesc;

        return $this;
    }

    /**
     * Get crDesc
     *
     * @return string 
     */
    public function getCrDesc()
    {
        return $this->crDesc;
    }

    /**
     * Set crJour
     *
     * @param string $crJour
     * @return Creneau
     */
    public function setCrJour($crJour)
    {
        $this->crJour = $crJour;

        return $this;
    }

    /**
     * Get crJour
     *
     * @return string 
     */
    public function getCrJour()
    {
        return $this->crJour;
    }

    /**
     * Set crHeure
     *
     * @param \DateTime $crHeure
     * @return Creneau
     */
    public function setCrHeure($crHeure)
    {
        $this->crHeure = $crHeure;

        return $this;
    }

    /**
     * Get crHeure
     *
     * @return \DateTime 
     */
    public function getCrHeure()
    {
        return $this->crHeure;
    }

    public function setCrDuree($crDuree)
    {
        $this->crDuree = $crDuree;

        return $this;
    }

    /**
     * Get crDuree
     *
     * @return integer 
     */
    public function getCrDuree()
    {
        return $this->crDuree;
    }
    

    /**
     * Get categorie
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCategorie()
    {
        return $this->categorie;
    }

    /**
     * Set categorie
     *
     * @param string $categorie
     * @return Creneau
     */
    public function setCategorie($categorie)
    {
        $this->categorie = $categorie;

        return $this;
    }
}
