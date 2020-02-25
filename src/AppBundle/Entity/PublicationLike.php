<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PublicationLike
 *
 * @ORM\Table(name="publication_like")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PublicationLikeRepository")
 */
class PublicationLike
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
     * @ORM\ManyToOne(targetEntity="Publication",inversedBy="likes")
     * @ORM\JoinColumn(name="publication_id",referencedColumnName="id")
     */
    private $publication;


    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User",inversedBy="likes")
     *@ORM\JoinColumn(name="utilisateur_id", referencedColumnName="id")
     */

    private $utilisateur;
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
     * @return mixed
     */
    public function getPublication()
    {
        return $this->publication;
    }

    /**
     * @param mixed $publication
     */
    public function setPublication($publication)
    {
        $this->publication = $publication;
    }

    /**
     * @return mixed
     */
    public function getUtilisateur()
    {
        return $this->utilisateur;
    }

    /**
     * @param mixed $utilisateur
     */
    public function setUtilisateur($utilisateur)
    {
        $this->utilisateur = $utilisateur;
    }



}

