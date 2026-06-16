<?php

namespace App\Repository;

use App\Entity\Enseignant;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Enseignant>
 */
class EnseignantRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Enseignant::class);
    }

    /**
     * Enseignants disponibles à un créneau, en excluant les IDs donnés (cross-filtrage jury).
     * $excludeSoutenanceId : ignorer cette soutenance (mode édition).
     *
     * @param  int[]       $excludeIds
     * @return Enseignant[]
     */
    public function findAvailableAt(\DateTimeInterface $date, \DateTimeInterface $heure, array $excludeIds = [], ?int $excludeSoutenanceId = null): array
    {
        $em       = $this->getEntityManager();
        $dateStr  = $date->format('Y-m-d');
        $heureStr = $heure->format('H:i:s');

        $busyIds = [];
        foreach (['president', 'rapporteur', 'examinateur'] as $role) {
            $q = $em->createQueryBuilder()
                ->select("IDENTITY(s.$role) AS ens_id")
                ->from(\App\Entity\Soutenance::class, 's')
                ->where('s.date = :date AND s.heure = :heure')
                ->setParameter('date', $dateStr)
                ->setParameter('heure', $heureStr);

            if ($excludeSoutenanceId) {
                $q->andWhere('s.id != :excId')->setParameter('excId', $excludeSoutenanceId);
            }

            foreach ($q->getQuery()->getScalarResult() as $row) {
                if ($row['ens_id'] !== null) {
                    $busyIds[] = (int) $row['ens_id'];
                }
            }
        }

        $allExclude = array_unique(array_merge($busyIds, array_map('intval', array_filter($excludeIds))));

        $qb = $this->createQueryBuilder('e')->orderBy('e.nom', 'ASC');
        if ($allExclude) {
            $qb->where('e.id NOT IN (:exclude)')->setParameter('exclude', $allExclude);
        }
        return $qb->getQuery()->getResult();
    }

    /** @return Enseignant[] */
    public function findAllWithUser(): array
    {
        return $this->createQueryBuilder('e')
            ->leftJoin('e.user', 'u')
            ->addSelect('u')
            ->orderBy('e.nom', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
