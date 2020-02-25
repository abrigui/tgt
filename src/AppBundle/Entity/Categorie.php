<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Categorie
 *
 * @ORM\Table(name="categorie")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CategorieRepository")
 */
class Categorie
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
     * @ORM\OneToMany(targetEntity="SousCategorie",mappedBy="Categorie")
     */
    private $id_sousCategorie;

    /**
     * @return mixed
     */
    public function getIdSousCategorie()
    {
        return $this->id_sousCategorie;
    }

    /**
     * @param mixed $id_sousCategorie
     */
    public function setIdSousCategorie($id_sousCategorie)
    {
        $this->id_sousCategorie = $id_sousCategorie;
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
     * @return Categorie
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

