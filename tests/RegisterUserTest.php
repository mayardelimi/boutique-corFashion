<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RegisterUserTest extends WebTestCase
{
    public function testSomething(): void
    {
        $client = static::createClient();
        $client->request('GET', '/inscription');

        $client->submitForm("S'inscrire", [
            'register_user[email]'                 => 'juliedoe@gmail.com',
            'register_user[plainPassword][first]'  => '1234',
            'register_user[plainPassword][second]' => '1234',
            'register_user[firstname]'             => 'julie',
            'register_user[lastname]'              => 'doe'
        ]);

        $this->assertResponseRedirects('/connexion');
        $client->followRedirect();

        $this->assertSelectorExists('div:contains("votre compte est cree")');
    }
}
