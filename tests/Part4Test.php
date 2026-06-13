<?php

namespace App\Tests;

use App\Entity\Enseignant;
use App\Entity\Etudiant;
use App\Entity\Salle;
use App\Entity\Soutenance;
use App\Repository\AppUserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class Part4Test extends WebTestCase
{
    public function testSoutenanceConflicts(): void
    {
        $client = static::createClient();
        $container = static::getContainer();
        $entityManager = $container->get('doctrine.orm.entity_manager');
        
        // Nettoyage radical
        $entityManager->getConnection()->executeStatement('DELETE FROM soutenance');

        $userRepository = $container->get(AppUserRepository::class);
        $testUser = $userRepository->findOneByEmail('admin@soutenance.pro');
        $client->loginUser($testUser);

        $etudiants = $entityManager->getRepository(Etudiant::class)->findAll();
        $salles = $entityManager->getRepository(Salle::class)->findAll();
        $enseignants = $entityManager->getRepository(Enseignant::class)->findAll();
        
        $e1 = $etudiants[0]->getId();
        $e2 = $etudiants[1]->getId();
        $s1 = $salles[0]->getId();
        $jury = [$enseignants[0]->getId(), $enseignants[1]->getId(), $enseignants[2]->getId()];

        // 1. Création réussie
        $crawler = $client->request('GET', '/admin/soutenance/new');
        $form = $crawler->selectButton('Confirmer la programmation')->form([
            'soutenance[etudiant]' => $e1,
            'soutenance[date]' => '2026-12-01',
            'soutenance[heure]' => '10:00',
            'soutenance[salle]' => $s1,
            'soutenance[president]' => $jury[0],
            'soutenance[rapporteur]' => $jury[1],
            'soutenance[examinateur]' => $jury[2],
        ]);
        $client->submit($form);
        $this->assertResponseRedirects('/admin/soutenance/');
        
        // VÉRIFICATION : Est-ce qu'elle est en base ?
        $entityManager->clear(); // On vide le cache ORM
        $count = $entityManager->getRepository(Soutenance::class)->count([]);
        $this->assertEquals(1, $count, "La première soutenance n'a pas été enregistrée en base.");

        // 2. Test Conflit
        $crawler = $client->request('GET', '/admin/soutenance/new');
        $form = $crawler->selectButton('Confirmer la programmation')->form([
            'soutenance[etudiant]' => $e2, 
            'soutenance[date]' => '2026-12-01',
            'soutenance[heure]' => '10:00',
            'soutenance[salle]' => $s1, 
            'soutenance[president]' => $jury[0],
            'soutenance[rapporteur]' => $jury[1],
            'soutenance[examinateur]' => $jury[2],
        ]);
        $client->submit($form);
        $this->assertSelectorTextContains('.alert-danger', 'CONFLIT');
    }
}
