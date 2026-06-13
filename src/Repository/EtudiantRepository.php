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

    /**
     * @return Etudiant[]
     */
    public function searchByNom(string $query): array
    {
        return $this->createQueryBuilder('e')
            ->where('e.nom LIKE :query OR e.prenom LIKE :query')
            ->setParameter('query', '%' . $query . '%')
            ->orderBy('e.nom', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
