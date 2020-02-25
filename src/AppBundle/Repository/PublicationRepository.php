<?php

namespace AppBundle\Repository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use InvalidArgumentException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * PublicationRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PublicationRepository extends \Doctrine\ORM\EntityRepository
{

    public function  publicationByUtilisateur($user)
    {
        return $this->getEntityManager()
            ->createQuery(
                'SELECT p 
                FROM AppBundle:Publication p 
                WHERE p.utilisateur =:id '
            )
            ->setParameter('id', $user)
            ->getResult();

    }

    public function findEntitiesByString($str){
        return $this->getEntityManager()
            ->createQuery(
                'SELECT p
                FROM AppBundle:Publication p
                WHERE p.contenu LIKE :str'
            )
            ->setParameter('str', '%'.$str.'%')
            ->getResult();
    }

    //pagination

    public function findAllPagineEtTrie($page, $nbMaxParPage)
    {
        if (!is_numeric($page)) {
            throw new InvalidArgumentException(
                'La valeur de l\'argument $page est incorrecte (valeur : ' . $page . ').'
            );
        }

        //si lavaleur de page entré dans l'url est inferieur a 1

        if ($page < 1) {
            throw new NotFoundHttpException('La page demandée n\'existe pas');
        }
        //si la valeur de nbr de page maximale est incorrect
        if (!is_numeric($nbMaxParPage)) {
            throw new InvalidArgumentException(
                'La valeur de l\'argument $nbMaxParPage est incorrecte (valeur : ' . $nbMaxParPage . ').'
            );
        }

        $qb = $this->createQueryBuilder('p')
            ->where('CURRENT_DATE() >= p.date')
            ->orderBy('p.date', 'DESC');

        $query = $qb->getQuery();

        $premierResultat = ($page - 1) * $nbMaxParPage;
        $query->setFirstResult($premierResultat)->setMaxResults($nbMaxParPage);
        $paginator = new Paginator($query);

        if ( ($paginator->count() <= $premierResultat) && $page != 1) {
            throw new NotFoundHttpException('La page demandée n\'existe pas.'); // page 404, sauf pour la première page
        }

        return $paginator;
    }

}
