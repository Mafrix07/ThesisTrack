<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SecurityTest extends WebTestCase
{
    public function testRedirectToLogin(): void
    {
        $client = static::createClient();
        $client->request('GET', '/');

        $this->assertResponseRedirects('/login');
    }

    public function testLoginWorks(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');

        $form = $crawler->selectButton('Se connecter')->form([
            '_username' => 'admin@soutenance.pro',
            '_password' => 'admin123',
        ]);

        $client->submit($form);
        $this->assertResponseRedirects('/');
        
        $client->followRedirect();
        $this->assertSelectorTextContains('h1', 'Bienvenue');
    }
}
