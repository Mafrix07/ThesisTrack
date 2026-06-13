<?php

namespace App\Tests;

use App\Repository\AppUserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class Part3Test extends WebTestCase
{
    public function testSalleCrud(): void
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(AppUserRepository::class);
        $testUser = $userRepository->findOneByEmail('admin@soutenance.pro');
        $client->loginUser($testUser);

        // List
        $client->request('GET', '/admin/salle/');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Liste des Salles');

        // New
        $crawler = $client->request('GET', '/admin/salle/new');
        $form = $crawler->selectButton('Enregistrer')->form([
            'salle[code]' => 'TEST_SALLE',
            'salle[capacite]' => 25,
            'salle[localisation]' => 'TEST_LOC',
        ]);
        $client->submit($form);
        $this->assertResponseRedirects('/admin/salle/');
        $client->followRedirect();
        $this->assertSelectorTextContains('body', 'TEST_SALLE');

        // Validation: Negative capacity
        $crawler = $client->request('GET', '/admin/salle/new');
        $form = $crawler->selectButton('Enregistrer')->form([
            'salle[code]' => 'ERR_SALLE',
            'salle[capacite]' => -5,
            'salle[localisation]' => 'ERR_LOC',
        ]);
        $client->submit($form);
        $this->assertSelectorTextContains('body', 'La capacité doit être supérieure à 0');
    }
}
