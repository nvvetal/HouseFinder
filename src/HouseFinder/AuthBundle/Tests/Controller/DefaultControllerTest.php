<?php

namespace HouseFinder\AuthBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/register');
        $this->assertEquals(301, $client->getResponse()->getStatusCode());
        $this->assertEquals('http://localhost/register/', $client->getResponse()->headers->get('location'));

        $crawler = $client->followRedirect();
        $this->assertTrue(
            $crawler->filter(
                'html:contains(\'You have requested a non-existent service "fos_user.registration.form"\')'
            )->count() > 0
        );
    }
}
