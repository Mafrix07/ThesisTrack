<?php

namespace App\Repository;

use App\Entity\Salle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Salle>
 */
class SalleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Salle::class);
    }

    /**
     * Salles libres à un créneau donné.
     * $excludeSoutenanceId : ignorer cette soutenance (mode édition).
     *
     * @return Salle[]
     */
    public function findAvailableAt(\DateTimeInterface $date, \DateTimeInterface $heure, ?int $excludeSoutenanceId = null): array
    {
        $qb = $this->getEntityManager()->createQueryBuilder()
            ->select('IDENTITY(s.salle) AS salle_id')
            ->from(\App\Entity\Soutenance::class, 's')
            ->where('s.date = :date AND s.heure = :heure')
            ->setParameter('date', $date->format('Y-m-d'))
            ->setParameter('heure', $heure->format('H:i:s'));

        if ($excludeSoutenanceId) {
            $qb->andWhere('s.id != :excId')->setParameter('excId', $excludeSoutenanceId);
        }

        $takenIds = array_column($qb->getQuery()->getScalarResult(), 'salle_id');

        $myQb = $this->createQueryBuilder('sa')->orderBy('sa.code', 'ASC');
        if ($takenIds) {
            $myQb->where('sa.id NOT IN (:taken)')->setParameter('taken', $takenIds);
        }
        return $myQb->getQuery()->getResult();
    }
}
