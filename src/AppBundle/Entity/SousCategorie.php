<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * SousCategorie
 *
 * @ORM\Table(name="sous_categorie")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\SousCategorieRepository")
 */
class SousCategorie
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
     * @ORM\Column(name="libelle", type="string", length=255)
     */
    private $libelle;



    /**
     * @ORM\OneToMany(targetEntity="Evenement",mappedBy="SousCategorie")
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
     * @ORM\ManyToOne(targetEntity="Categorie",inversedBy="SousCategorie")
     * @ORM\JoinColumn(name="categorie_id",referencedColumnName="id")
     * @Assert\NotBlank
     */
    private $id_categorie;

    /**
     * @return mixed
     */
    public function getIdCategorie()
    {
        return $this->id_categorie;
    }

    /**
     * @param mixed $id_categorie
     */
    public function setIdCategorie($id_categorie)
    {
        $this->id_categorie = $id_categorie;
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
     * Set libelle
     *
     * @param string $libelle
     *
     * @return SousCategorie
     */
    public function setLibelle($libelle)
    {
        $this->libelle = $libelle;

        return $this;
    }

    /**
     * Get libelle
     *
     * @return string
     */
    public function getLibelle()
    {
        return $this->libelle;
    }

    public function __toString()
    {
        // TODO: Implement __toString() method.
        return $this->libelle;
    }
}

