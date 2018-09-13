<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Utilisateur
 *
 * @ORM\Table(name="utilisateur")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UtilisateurRepository")
 */
class Utilisateur
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
     * @ORM\Column(name="nom", type="string", length=255, unique=true)
     */
    private $nom;

    /**
     * @ORM\OneToMany(targetEntity="\AppBundle\Entity\Message", mappedBy="destinataire")
     */
    private $messagesRecu;

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
     * Set nom
     *
     * @param string $nom
     *
     * @return Utilisateur
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
     * Constructor
     */
    public function __construct()
    {
        $this->messagesRecu = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add messagesRecu
     *
     * @param \AppBundle\Entity\Message $messagesRecu
     *
     * @return Utilisateur
     */
    public function addMessagesRecu(\AppBundle\Entity\Message $messagesRecu)
    {
        $this->messagesRecu[] = $messagesRecu;

        return $this;
    }

    /**
     * Remove messagesRecu
     *
     * @param \AppBundle\Entity\Message $messagesRecu
     */
    public function removeMessagesRecu(\AppBundle\Entity\Message $messagesRecu)
    {
        $this->messagesRecu->removeElement($messagesRecu);
    }

    /**
     * Get messagesRecu
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMessagesRecu()
    {
        return $this->messagesRecu;
    }
}
