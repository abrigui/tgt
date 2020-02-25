<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Participation
 *
 * @ORM\Table(name="participation")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ParticipationRepository")
 */
class Participation
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
     * @ORM\Column(name="utilisateur", type="string", length=255)
     */
    private $utilisateur;
    /**
     * @var string
     *
     * @ORM\Column(name="evenement", type="string", length=255)
     */
    private $evenement;
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
     * Set utilisateur
     *
     * @param string $utilisateur
     *
     * @return Participation
     */
    public function setUtilisateur($utilisateur)
    {
        $this->utilisateur = $utilisateur;
        return $this;
    }
    /**
     * Get utilisateur
     *
     * @return string
     */
    public function getUtilisateur()
    {
        return $this->utilisateur;
    }
    /**
     * Set evenement
     *
     * @param string $evenement
     *
     * @return Participation
     */
    public function setEvenement($evenement)
    {
        $this->evenement = $evenement;
        return $this;
    }
    /**
     * Get evenement
     *
     * @return string
     */
    public function getEvenement()
    {
        return $this->evenement;
    }
}

