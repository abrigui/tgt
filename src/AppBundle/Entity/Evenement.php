<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Evenement
 *
 * @ORM\Table(name="evenement")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\EvenementRepository")
 */
class Evenement
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
     * @Assert\NotBlank
     * @ORM\Column(name="nom", type="string", length=255)
     */
    private $nom;
    /**
     * @ORM\ManyToOne(targetEntity="SousCategorie",inversedBy="Evenement")
     * @ORM\JoinColumn(name="SousCategorie_id",referencedColumnName="id")
     * @Assert\NotBlank
     */
    private $sousCategorie;


    /**
     * @return mixed
     */
    public function getSousCategorie()
    {
        return $this->sousCategorie;
    }

    /**
     * @param mixed $sousCategorie
     */
    public function setSousCategorie($sousCategorie)
    {
        $this->sousCategorie = $sousCategorie;
    }

    /**
     * @ORM\ManyToOne(targetEntity="Agence",inversedBy="Evenement")
     * @ORM\JoinColumn(name="Agence_id",referencedColumnName="id")
     * @Assert\NotBlank
     */
    private $agence;

    /**
     * @return mixed
     */
    public function getAgence()
    {
        return $this->agence;
    }

    /**
     * @param mixed $agence
     */
    public function setAgence($agence)
    {
        $this->agence = $agence;
    }

    /**
     * @var string
     * @ORM\Column(name="image",type="string")
     * @Assert\NotBlank
     */
    private $image;

    /**
     * @return mixed
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param mixed $image
     */
    public function setImage($image)
    {
        $this->image = $image;
    }

    /**
     * @ORM\ManyToMany(targetEntity="User")
     * @ORM\JoinTable(name="participants",
     *     joinColumns={@ORM\JoinColumn(name="evenement_id",referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="utilisateurTalent_id",referencedColumnName="id")})
     *
     */
    private $utilisateurTalent;

    /**
     * @return mixed
     */
    public function getUtilisateurTalent()
    {
        return $this->utilisateurTalent;
    }

    /**
     * @param mixed $utilisateurTalent
     */
    public function setUtilisateurTalent($utilisateurTalent)
    {
        $this->utilisateurTalent = $utilisateurTalent;
    }

    /**
     * @return \DateTime
     */
    public function getDateDebut()
    {
        return $this->dateDebut;
    }

    /**
     * @param \DateTime $dateDebut
     */
    public function setDateDebut($dateDebut)
    {
        $this->dateDebut = $dateDebut;
    }


    /**
     * @var string
     *@Assert\NotBlank
     * @ORM\Column(name="description", type="string", length=255)
     */
    private $description;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateDebut", type="date")
     */
    private $dateDebut;

    /**
     * @var string
     *@Assert\NotBlank
     * @ORM\Column(name="lieu", type="string", length=255)
     */
    private $lieu;

    /**
     * @var \DateTime
     * @Assert\GreaterThan(propertyPath="dateDebut")
     * @ORM\Column(name="dateFin", type="date")
     */
    private $dateFin;

    /**
     * @var int
     * @Assert\NotBlank
     * @ORM\Column(name="nbParticipantMax", type="integer")
     */
    private $nbParticipantMax;


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
     * @return Evenement
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
     * Set description
     *
     * @param string $description
     *
     * @return Evenement
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Evenement
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

    /**
     * Set lieu
     *
     * @param string $lieu
     *
     * @return Evenement
     */
    public function setLieu($lieu)
    {
        $this->lieu = $lieu;

        return $this;
    }

    /**
     * Get lieu
     *
     * @return string
     */
    public function getLieu()
    {
        return $this->lieu;
    }

    /**
     * Set dateFin
     *
     * @param \DateTime $dateFin
     *
     * @return Evenement
     */
    public function setDateFin($dateFin)
    {
        $this->dateFin = $dateFin;

        return $this;
    }

    /**
     * Get dateFin
     *
     * @return \DateTime
     */
    public function getDateFin()
    {
        return $this->dateFin;
    }

    /**
     * Set nbParticipantMax
     *
     * @param integer $nbParticipantMax
     *
     * @return Evenement
     */
    public function setNbParticipantMax($nbParticipantMax)
    {
        $this->nbParticipantMax = $nbParticipantMax;

        return $this;
    }

    /**
     * Get nbParticipantMax
     *
     * @return int
     */
    public function getNbParticipantMax()
    {
        return $this->nbParticipantMax;
    }



}

