<?php

namespace App\Repository;

use App\Entity\Etudiant;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Etudiant>
 */
class EtudiantRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Etudiant::class);
    }

    /** @return Etudiant[] */
    public function searchByNom(string $query): array
    {
        return $this->createQueryBuilder('e')
            ->where('e.nom LIKE :query OR e.prenom LIKE :query')
            ->setParameter('query', '%' . $query . '%')
            ->orderBy('e.nom', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Etudiants sans soutenance planifiée.
     * En mode édition, passer l'ID de l'étudiant courant pour qu'il reste visible.
     *
     * @return Etudiant[]
     */
    public function findWithoutSoutenance(?int $currentEtudiantId = null): array
    {
        $takenIds = array_column(
            $this->getEntityManager()
                 ->createQuery('SELECT IDENTITY(s.etudiant) AS eid FROM App\Entity\Soutenance s')
                 ->getScalarResult(),
            'eid'
        );

        if ($currentEtudiantId !== null) {
            $takenIds = array_values(array_filter($takenIds, fn($id) => (int)$id !== $currentEtudiantId));
        }

        $qb = $this->createQueryBuilder('e')->orderBy('e.nom', 'ASC');
        if ($takenIds) {
            $qb->where('e.id NOT IN (:taken)')->setParameter('taken', $takenIds);
        }
        return $qb->getQuery()->getResult();
    }
}
