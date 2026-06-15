<?php

namespace App\Controller;

use App\Repository\EnseignantRepository;
use App\Repository\EtudiantRepository;
use App\Repository\SalleRepository;
use App\Repository\SoutenanceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
class DashboardController extends AbstractController
{
    #[Route('/', name: 'app_dashboard')]
    public function index(
        EtudiantRepository $etudiantRepository,
        EnseignantRepository $enseignantRepository,
        SalleRepository $salleRepository,
        SoutenanceRepository $soutenanceRepository
    ): Response {
        $data = [];

        if ($this->isGranted('ROLE_ADMIN')) {
            $data['stats'] = [
                'etudiants' => $etudiantRepository->count([]),
                'enseignants' => $enseignantRepository->count([]),
                'salles' => $salleRepository->count([]),
                'soutenances' => $soutenanceRepository->count([]),
            ];
        }

        if ($this->isGranted('ROLE_ENSEIGNANT')) {
            // Récupérer l'entité Enseignant liée à l'utilisateur connecté
            $enseignant = $enseignantRepository->findOneBy(['user' => $this->getUser()]);
            
            if ($enseignant) {
                $soutenances = $soutenanceRepository->findByEnseignant($enseignant);
                $data['enseignant_data'] = [
                    'soutenances' => $soutenances,
                    'count' => count($soutenances),
                ];
            } else {
                $data['enseignant_data'] = [
                    'soutenances' => [],
                    'count' => 0,
                ];
            }
        }

        return $this->render('dashboard/index.html.twig', $data);
    }
}
