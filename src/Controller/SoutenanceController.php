<?php

namespace App\Controller;

use App\Entity\Soutenance;
use App\Form\SoutenanceType;
use App\Repository\SoutenanceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
        if ($dateStr) {
            $date = \DateTime::createFromFormat('Y-m-d', $dateStr);
            $soutenances = $soutenanceRepository->findByDate($date);
        } else {
            $soutenances = $soutenanceRepository->findAll();
        }

        return $this->render('soutenance/index.html.twig', [
            'soutenances' => $soutenances,
            'current_date' => $dateStr,
        ]);
    }

    #[Route('/new', name: 'app_soutenance_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, SoutenanceRepository $soutenanceRepository): Response
    {
        $soutenance = new Soutenance();
        $form = $this->createForm(SoutenanceType::class, $soutenance);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($this->hasConflicts($soutenance, $soutenanceRepository)) {
                return $this->render('soutenance/new.html.twig', [
                    'soutenance' => $soutenance,
                    'form' => $form,
                ]);
            }

            $entityManager->persist($soutenance);
            $entityManager->flush();

            $this->addFlash('success', 'Soutenance programmée avec succès.');
            return $this->redirectToRoute('app_soutenance_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('soutenance/new.html.twig', [
            'soutenance' => $soutenance,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_soutenance_show', methods: ['GET'])]
    public function show(Soutenance $soutenance): Response
    {
        return $this->render('soutenance/show.html.twig', [
            'soutenance' => $soutenance,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_soutenance_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Soutenance $soutenance, EntityManagerInterface $entityManager, SoutenanceRepository $soutenanceRepository): Response
    {
        $form = $this->createForm(SoutenanceType::class, $soutenance);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($this->hasConflicts($soutenance, $soutenanceRepository)) {
                return $this->render('soutenance/edit.html.twig', [
                    'soutenance' => $soutenance,
                    'form' => $form,
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

    private function hasConflicts(Soutenance $soutenance, SoutenanceRepository $repository): bool
    {
        $date = $soutenance->getDate();
        $heure = $soutenance->getHeure();
        $salle = $soutenance->getSalle();
        $id = $soutenance->getId();

        // 1. Conflit Salle
        $qb = $repository->createQueryBuilder('s')
            ->select('count(s.id)')
            ->where('s.date = :date AND s.heure = :heure AND s.salle = :salle')
            ->setParameter('date', $date->format('Y-m-d'))
            ->setParameter('heure', $heure->format('H:i:s'))
            ->setParameter('salle', $salle);
        
        if ($id) { $qb->andWhere('s.id != :id')->setParameter('id', $id); }

        if ((int) $qb->getQuery()->getSingleScalarResult() > 0) {
            $this->addFlash('danger', "CONFLIT : La salle {$salle->getCode()} est déjà occupée à cette heure.");
            return true;
        }

        // 2. Conflit Jury
        $jury = [$soutenance->getPresident(), $soutenance->getRapporteur(), $soutenance->getExaminateur()];
        foreach ($jury as $ens) {
            $qbEns = $repository->createQueryBuilder('s')
                ->select('count(s.id)')
                ->where('s.date = :date AND s.heure = :heure')
                ->andWhere('(s.president = :ens OR s.rapporteur = :ens OR s.examinateur = :ens)')
                ->setParameter('date', $date->format('Y-m-d'))
                ->setParameter('heure', $heure->format('H:i:s'))
                ->setParameter('ens', $ens);

            if ($id) { $qbEns->andWhere('s.id != :id')->setParameter('id', $id); }

            if ((int) $qbEns->getQuery()->getSingleScalarResult() > 0) {
                $this->addFlash('danger', "CONFLIT : L'enseignant {$ens->getNom()} est déjà dans un jury à cette heure.");
                return true;
            }
        }

        return false;
    }
}
