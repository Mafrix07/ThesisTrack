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

    public function findUpcoming(int $limit = 5): array
    {
        return $this->createQueryBuilder('s')
            ->leftJoin('s.etudiant', 'et')->addSelect('et')
            ->leftJoin('s.salle', 'sa')->addSelect('sa')
            ->where('s.date >= :today')
            ->setParameter('today', (new \DateTime('today'))->format('Y-m-d'))
            ->orderBy('s.date', 'ASC')
            ->addOrderBy('s.heure', 'ASC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    public function search(string $query): array
    {
        $q = '%' . strtolower($query) . '%';
        return $this->createQueryBuilder('s')
            ->leftJoin('s.etudiant', 'et')->addSelect('et')
            ->leftJoin('s.salle', 'sa')->addSelect('sa')
            ->leftJoin('s.president', 'p')->addSelect('p')
            ->leftJoin('s.rapporteur', 'r')->addSelect('r')
            ->leftJoin('s.examinateur', 'ex')->addSelect('ex')
            ->where('LOWER(et.nom) LIKE :q OR LOWER(et.prenom) LIKE :q')
            ->orWhere('LOWER(p.nom) LIKE :q OR LOWER(r.nom) LIKE :q OR LOWER(ex.nom) LIKE :q')
            ->setParameter('q', $q)
            ->orderBy('s.date', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function countByFiliere(): array
    {
        return $this->createQueryBuilder('s')
            ->select('et.filiere AS filiere', 'COUNT(s.id) AS total')
            ->innerJoin('s.etudiant', 'et')
            ->groupBy('et.filiere')
            ->orderBy('total', 'DESC')
            ->getQuery()
            ->getScalarResult();
    }

    public function findAllWithRelations(): array
    {
        return $this->createQueryBuilder('s')
            ->leftJoin('s.etudiant', 'et')->addSelect('et')
            ->leftJoin('s.salle', 'sa')->addSelect('sa')
            ->leftJoin('s.president', 'p')->addSelect('p')
            ->leftJoin('s.rapporteur', 'r')->addSelect('r')
            ->leftJoin('s.examinateur', 'ex')->addSelect('ex')
            ->orderBy('s.date', 'ASC')
            ->addOrderBy('s.heure', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findByDate(\DateTimeInterface $date): array
    {
        return $this->createQueryBuilder('s')
            ->leftJoin('s.etudiant', 'et')->addSelect('et')
            ->leftJoin('s.salle', 'sa')->addSelect('sa')
            ->leftJoin('s.president', 'p')->addSelect('p')
            ->leftJoin('s.rapporteur', 'r')->addSelect('r')
            ->leftJoin('s.examinateur', 'ex')->addSelect('ex')
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
            ->leftJoin('s.etudiant', 'et')->addSelect('et')
            ->leftJoin('s.salle', 'sa')->addSelect('sa')
            ->leftJoin('s.president', 'p')->addSelect('p')
            ->leftJoin('p.user', 'pu')->addSelect('pu')
            ->leftJoin('s.rapporteur', 'r')->addSelect('r')
            ->leftJoin('r.user', 'ru')->addSelect('ru')
            ->leftJoin('s.examinateur', 'ex')->addSelect('ex')
            ->leftJoin('ex.user', 'exu')->addSelect('exu')
            ->where('s.president = :ens OR s.rapporteur = :ens OR s.examinateur = :ens')
            ->setParameter('ens', $enseignant)
            ->orderBy('s.date', 'ASC')
            ->addOrderBy('s.heure', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
