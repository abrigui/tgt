<?php

namespace AppBundle\Repository;

/**
 * ParticipationRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ParticipationRepository extends \Doctrine\ORM\EntityRepository
{
    public function findParticipant($str,$eve){
        return $this->getEntityManager()
            ->createQuery(
                'SELECT p
                FROM AppBundle:Participation p
                WHERE p.utilisateur LIKE :str AND p.evenement LIKE :eve'
            )
            ->setParameter('str', $str)
            ->setParameter('eve', $eve)
            ->getResult();
    }
    public function findNombreParticipantParEvenement($eve){
        return $this->getEntityManager()
            ->createQuery(
                'SELECT count(p)
                FROM AppBundle:Participation p
                WHERE p.evenement LIKE :eve'
            )
            ->setParameter('eve', $eve)
            ->getSingleResult();
    }
}