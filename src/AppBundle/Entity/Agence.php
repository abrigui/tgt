<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Agence
 *
 * @ORM\Table(name="agence")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\AgenceRepository")
 */
class Agence
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
     * @ORM\ManyToOne(targetEntity="Sponsor",inversedBy="agence")
     * @ORM\JoinColumn(name="sponsor_id",referencedColumnName="id")
     */
    private $sponsor;


    /**
     * @return string
     */
    public function getLogo()
    {
        return $this->logo;
    }

    /**
     * @param string $logo
     */
    public function setLogo($logo)
    {
        $this->logo = $logo;
    }

    /**
     * @var string
     *
     * @ORM\Column(name="logo", type="string", length=255)
     */
    private $logo;



    /**
     * @return string
     */
    public function getSponsor()
    {
        return $this->sponsor;
    }

    /**
     * @param string $sponsor
     */
    public function setSponsor($sponsor)
    {
        $this->sponsor = $sponsor;
    }







    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=255)
     * @Assert\Length(min=3, max=15,minMessage="hhhhhhh" )
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="matriculeFiscale", type="string", length=255)
     * @Assert\Length(min=5, max=15 )
     */
    private $matriculeFiscale;

    /**
     * @var string
     *
     * @ORM\Column(name="adresse", type="string", length=255)
     * @Assert\Length(min=10, max=30 )
     */
    private $adresse;

    /**
     * @var int
     *
     * @ORM\Column(name="telephone", type="integer")
     */
    private $telephone;

    /**
     * @var int
     *
     * @ORM\Column(name="fax", type="integer")
     */
    private $fax;

    /**
     * @var int
     *
     * @ORM\Column(name="etat", type="integer")
     */
    private $etat;

    /**
     * @var string
     *
     * @ORM\Column(name="site", type="string", length=255)
     * @Assert\Length(min=10, max=25 )
     */
    private $site;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255)
     * @Assert\Length(min=10, max=30 )
     */
    private $email;

    /**
     * @ORM\OneToMany(targetEntity="Evenement",mappedBy="Agence")
     */
    private $id_evenement;

    /**
     * @return mixed
     */
    public function getIdEvenement()
    {
        return $this->id_evenement;
    }

    /**
     * @param mixed $id_evenement
     */
    public function setIdEvenement($id_evenement)
    {
        $this->id_evenement = $id_evenement;
    }

    /**
     * @ORM\ManyToOne(targetEntity="User",inversedBy="Agence")
     * @ORM\JoinColumn(name="utilisateurProfessionnel_id",referencedColumnName="id")
     */
    private $utilisateurProfessionnel;

    /**
     * @return mixed
     */
    public function getUtilisateurProfessionnel()
    {
        return $this->utilisateurProfessionnel;
    }

    /**
     * @param mixed $utilisateurProfessionnel
     */
    public function setUtilisateurProfessionnel($utilisateurProfessionnel)
    {
        $this->utilisateurProfessionnel = $utilisateurProfessionnel;
    }


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
     * @return Agence
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
     * Set matriculeFiscale
     *
     * @param string $matriculeFiscale
     *
     * @return Agence
     */
    public function setMatriculeFiscale($matriculeFiscale)
    {
        $this->matriculeFiscale = $matriculeFiscale;

        return $this;
    }

    /**
     * Get matriculeFiscale
     *
     * @return string
     */
    public function getMatriculeFiscale()
    {
        return $this->matriculeFiscale;
    }

    /**
     * Set adresse
     *
     * @param string $adresse
     *
     * @return Agence
     */
    public function setAdresse($adresse)
    {
        $this->adresse = $adresse;

        return $this;
    }

    /**
     * Get adresse
     *
     * @return string
     */
    public function getAdresse()
    {
        return $this->adresse;
    }

    /**
     * Set telephone
     *
     * @param integer $telephone
     *
     * @return Agence
     */
    public function setTelephone($telephone)
    {
        $this->telephone = $telephone;

        return $this;
    }

    /**
     * Get telephone
     *
     * @return int
     */
    public function getTelephone()
    {
        return $this->telephone;
    }

    /**
     * Set fax
     *
     * @param integer $fax
     *
     * @return Agence
     */
    public function setFax($fax)
    {
        $this->fax = $fax;

        return $this;
    }

    /**
     * Get fax
     *
     * @return int
     */
    public function getFax()
    {
        return $this->fax;
    }

    /**
     * Set site
     *
     * @param string $site
     *
     * @return Agence
     */
    public function setSite($site)
    {
        $this->site = $site;

        return $this;
    }

    /**
     * Get site
     *
     * @return string
     */
    public function getSite()
    {
        return $this->site;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return Agence
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    public function __toString()
    {
        // TODO: Implement __toString() method.
        return $this->nom;
    }

    /**
     * @return int
     */
    public function getEtat()
    {
        return $this->etat;
    }

    /**
     * @param int $etat
     */
    public function setEtat($etat)
    {
        $this->etat = $etat;
    }


}

