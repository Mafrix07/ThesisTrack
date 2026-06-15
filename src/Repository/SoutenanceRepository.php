<?php

namespace App\Repository;

use App\Entity\Soutenance;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Soutenance>
 */
class SoutenanceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Soutenance::class);
    }

    public function findByDate(\DateTimeInterface $date): array
    {
        return $this->createQueryBuilder('s')
            ->where('s.date = :date')
            ->setParameter('date', $date->format('Y-m-d'))
            ->orderBy('s.heure', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Soutenance[]
     */
    public function findByEnseignant($enseignant): array
    {
        return $this->createQueryBuilder('s')
            ->where('s.president = :ens OR s.rapporteur = :ens OR s.examinateur = :ens')
            ->setParameter('ens', $enseignant)
            ->orderBy('s.date', 'ASC')
            ->addOrderBy('s.heure', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
