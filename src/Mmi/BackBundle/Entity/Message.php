<?php

namespace Mmi\BackBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Message
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Mmi\BackBundle\Entity\MessageRepository")
 */
class Message
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
     * @ORM\Column(name="msg_titre", type="string", length=150)
     */
    private $msgTitre;

    /**
     * @var text
     *
     * @ORM\Column(name="msg_text", type="text", length=65535)
     */
    private $msgText;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="msg_date", type="date")
     */
    private $msgDate;

    /**
     * @var string
     *
     * @ORM\Column(name="msg_type", type="string", length=255)
     */
    private $msgType;

    /**
     * @ORM\ManyToMany(targetEntity="Mmi\BackBundle\Entity\User",inversedBy="message")
     * @ORM\JoinTable(name="message_user")
     *
     */
    private $user;

    /**
     * @var integer
     *
     * @ORM\Column(name="msg_voir", type="integer", length=1)
     */
    
    private $voir;




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
     * Set msgTitre
     *
     * @param string $msgTitre
     * @return Message
     */
    public function setMsgTitre($msgTitre)
    {
        $this->msgTitre = $msgTitre;

        return $this;
    }

    /**
     * Get msgTitre
     *
     * @return string 
     */
    public function getMsgTitre()
    {
        return $this->msgTitre;
    }

    /**
     * Set msgText
     *
     * @param string $msgText
     * @return Message
     */
    public function setMsgText($msgText)
    {
        $this->msgText = $msgText;

        return $this;
    }

    /**
     * Get msgText
     *
     * @return string 
     */
    public function getMsgText()
    {
        return $this->msgText;
    }

    /**
     * Set msgDate
     *
     * @param \DateTime $msgDate
     * @return Message
     */
    public function setMsgDate($msgDate)
    {
        $this->msgDate = $msgDate;

        return $this;
    }

    /**
     * Get msgDate
     *
     * @return \DateTime 
     */
    public function getMsgDate()
    {
        return $this->msgDate;
    }

    /**
     * Set msgType
     *
     * @param string $msgType
     * @return Message
     */
    public function setMsgType($msgType)
    {
        $this->msgType = $msgType;

        return $this;
    }

    /**
     * Get msgType
     *
     * @return string 
     */
    public function getMsgType()
    {
        return $this->msgType;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->message = new \Doctrine\Common\Collections\ArrayCollection();
    }


    /**
     * Add user
     *
     * @param \Mmi\BackBundle\Entity\User $user
     * @return Message
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
     * Set voir
     *
     * @param integer $voir
     * @return Message
     */
    public function setVoir($voir)
    {
        $this->voir = $voir;

        return $this;
    }

    /**
     * Get voir
     *
     * @return integer 
     */
    public function getVoir()
    {
        return $this->voir;
    }
}
