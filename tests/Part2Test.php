<?php

namespace App\Tests;

use App\Repository\AppUserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class Part2Test extends WebTestCase
{
    public function testEtudiantCrud(): void
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(AppUserRepository::class);
        $testUser = $userRepository->findOneByEmail('admin@soutenance.pro');
        $client->loginUser($testUser);

        // List
        $crawler = $client->request('GET', '/admin/etudiant/');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Liste des Étudiants');

        // New
        $crawler = $client->request('GET', '/admin/etudiant/new');
        $form = $crawler->selectButton('Enregistrer')->form([
            'etudiant[nom]' => 'TEST_NOM',
            'etudiant[prenom]' => 'TEST_PRENOM',
            'etudiant[email]' => 'test_etudiant@univ.test',
            'etudiant[filiere]' => 'TEST_FILIERE',
            'etudiant[themeMemoire]' => 'TEST_THEME',
        ]);
        $client->submit($form);
        $this->assertResponseRedirects('/admin/etudiant/');
        $client->followRedirect();
        $this->assertSelectorTextContains('body', 'TEST_NOM');

        // Search
        $client->request('GET', '/admin/etudiant/?search=TEST_NOM');
        $this->assertSelectorTextContains('body', 'TEST_NOM');
        
        $client->request('GET', '/admin/etudiant/?search=INEXISTANT');
        $this->assertSelectorTextContains('body', 'Aucun étudiant trouvé');
    }

    public function testEnseignantCrud(): void
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(AppUserRepository::class);
        $testUser = $userRepository->findOneByEmail('admin@soutenance.pro');
        $client->loginUser($testUser);

        // List
        $client->request('GET', '/admin/enseignant/');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Liste des Enseignants');

        // New
        $crawler = $client->request('GET', '/admin/enseignant/new');
        $form = $crawler->selectButton('Enregistrer')->form([
            'enseignant[nom]' => 'ENS_NOM',
            'enseignant[prenom]' => 'ENS_PRENOM',
            'enseignant[email]' => 'ens@univ.test',
            'enseignant[specialite]' => 'ENS_SPEC',
        ]);
        $client->submit($form);
        $this->assertResponseRedirects('/admin/enseignant/');
        $client->followRedirect();
        $this->assertSelectorTextContains('body', 'ENS_NOM');
    }
}
