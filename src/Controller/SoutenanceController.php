<?php

namespace App\Controller;

use App\Entity\Enseignant;
use App\Entity\Salle;
use App\Entity\Soutenance;
use App\Form\SoutenanceType;
use App\Repository\EnseignantRepository;
use App\Repository\SalleRepository;
use App\Repository\SoutenanceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin/soutenance')]
#[IsGranted('ROLE_ADMIN')]
class SoutenanceController extends AbstractController
{
    #[Route('/', name: 'app_soutenance_index', methods: ['GET'])]
    public function index(Request $request, SoutenanceRepository $soutenanceRepository): Response
    {
        $dateStr = $request->query->get('date');
        $search  = trim((string) $request->query->get('search', ''));

        if ($search !== '') {
            $soutenances = $soutenanceRepository->search($search);
        } elseif ($dateStr) {
            $date = \DateTime::createFromFormat('Y-m-d', $dateStr);
            $soutenances = $date !== false
                ? $soutenanceRepository->findByDate($date)
                : $soutenanceRepository->findAllWithRelations();
        } else {
            $soutenances = $soutenanceRepository->findAllWithRelations();
        }

        return $this->render('soutenance/index.html.twig', [
            'soutenances'  => $soutenances,
            'current_date' => $dateStr,
            'search'       => $search,
        ]);
    }

    #[Route('/new', name: 'app_soutenance_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, SoutenanceRepository $soutenanceRepository): Response
    {
        $soutenance = new Soutenance();
        $form = $this->createForm(SoutenanceType::class, $soutenance, ['validation_groups' => ['Default', 'creation']]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $conflicts = $this->collectConflicts($soutenance, $soutenanceRepository);
            if ($conflicts) {
                return $this->render('soutenance/new.html.twig', [
                    'soutenance'       => $soutenance,
                    'form'             => $form,
                    'conflict_errors'  => $conflicts,
                ]);
            }

            $entityManager->persist($soutenance);
            $entityManager->flush();

            $this->addFlash('success', 'Soutenance programmée avec succès.');
            return $this->redirectToRoute('app_soutenance_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('soutenance/new.html.twig', [
            'soutenance' => $soutenance,
            'form'       => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_soutenance_show', methods: ['GET'])]
    public function show(Soutenance $soutenance): Response
    {
        return $this->render('soutenance/show.html.twig', [
            'soutenance' => $soutenance,
        ]);
    }

    #[Route('/api/disponibles', name: 'app_soutenance_api_disponibles', methods: ['GET'])]
    public function apiDisponibles(Request $request, SalleRepository $salleRepo, EnseignantRepository $enseignantRepo): JsonResponse
    {
        $dateStr      = $request->query->getString('date');
        $heureStr     = $request->query->getString('heure');
        $soutenanceId = $request->query->getInt('soutenance') ?: null;
        $excludeIds   = array_filter(array_map('intval', $request->query->all('exclude')));

        $date  = $dateStr  ? \DateTime::createFromFormat('Y-m-d', $dateStr)  : false;
        $heure = $heureStr ? \DateTime::createFromFormat('H:i',   $heureStr) : false;

        if (!$date || !$heure) {
            return $this->json(['salles' => [], 'enseignants' => []]);
        }

        $salles      = $salleRepo->findAvailableAt($date, $heure, $soutenanceId);
        $enseignants = $enseignantRepo->findAvailableAt($date, $heure, array_values($excludeIds), $soutenanceId);

        return $this->json([
            'salles' => array_map(fn(Salle $s) => [
                'id'    => $s->getId(),
                'label' => $s->getCode().' — '.$s->getLocalisation().' (cap. '.$s->getCapacite().')',
            ], $salles),
            'enseignants' => array_map(fn(Enseignant $e) => [
                'id'    => $e->getId(),
                'label' => $e->getPrenom().' '.$e->getNom().' ('.$e->getSpecialite().')',
            ], $enseignants),
        ]);
    }

    #[Route('/{id}/edit', name: 'app_soutenance_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Soutenance $soutenance, EntityManagerInterface $entityManager, SoutenanceRepository $soutenanceRepository): Response
    {
        $form = $this->createForm(SoutenanceType::class, $soutenance, [
            'validation_groups'   => ['Default'],
            'current_etudiant_id' => $soutenance->getEtudiant()?->getId(),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $conflicts = $this->collectConflicts($soutenance, $soutenanceRepository);
            if ($conflicts) {
                return $this->render('soutenance/edit.html.twig', [
                    'soutenance'      => $soutenance,
                    'form'            => $form,
                    'conflict_errors' => $conflicts,
                ]);
            }

            $entityManager->flush();

            $this->addFlash('success', 'Soutenance modifiée avec succès.');
            return $this->redirectToRoute('app_soutenance_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('soutenance/edit.html.twig', [
            'soutenance' => $soutenance,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_soutenance_delete', methods: ['POST'])]
    public function delete(Request $request, Soutenance $soutenance, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$soutenance->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($soutenance);
            $entityManager->flush();
            $this->addFlash('success', 'Soutenance annulée.');
        }

        return $this->redirectToRoute('app_soutenance_index', [], Response::HTTP_SEE_OTHER);
    }

    /** @return string[] */
    private function collectConflicts(Soutenance $soutenance, SoutenanceRepository $repository): array
    {
        $date  = $soutenance->getDate();
        $heure = $soutenance->getHeure();
        $salle = $soutenance->getSalle();
        $id    = $soutenance->getId();
        $errors = [];

        $dateStr  = $date->format('Y-m-d');
        $heureStr = $heure->format('H:i:s');

        // 1. Conflit de salle
        $qb = $repository->createQueryBuilder('s')
            ->select('count(s.id)')
            ->where('s.date = :date AND s.heure = :heure AND s.salle = :salle')
            ->setParameter('date', $dateStr)
            ->setParameter('heure', $heureStr)
            ->setParameter('salle', $salle);

        if ($id) { $qb->andWhere('s.id != :id')->setParameter('id', $id); }

        if ((int) $qb->getQuery()->getSingleScalarResult() > 0) {
            $errors[] = "Conflit : la salle {$salle->getCode()} est déjà occupée à cette heure.";
        }

        // 2. Conflits de jury (tous vérifiés indépendamment)
        $jury = [
            'président'   => $soutenance->getPresident(),
            'rapporteur'  => $soutenance->getRapporteur(),
            'examinateur' => $soutenance->getExaminateur(),
        ];

        foreach ($jury as $role => $ens) {
            if (!$ens) { continue; }

            $qbEns = $repository->createQueryBuilder('s')
                ->select('count(s.id)')
                ->where('s.date = :date AND s.heure = :heure')
                ->andWhere('(s.president = :ens OR s.rapporteur = :ens OR s.examinateur = :ens)')
                ->setParameter('date', $dateStr)
                ->setParameter('heure', $heureStr)
                ->setParameter('ens', $ens);

            if ($id) { $qbEns->andWhere('s.id != :id')->setParameter('id', $id); }

            if ((int) $qbEns->getQuery()->getSingleScalarResult() > 0) {
                $errors[] = "Conflit : {$ens->getPrenom()} {$ens->getNom()} ({$role}) est déjà dans un jury à cette heure.";
            }
        }

        return $errors;
    }
}
