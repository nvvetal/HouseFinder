<?php

namespace HouseFinder\StorageBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();

        $crawler = $client->request('POST', '/storage/advertisement/photo/-1');
        $this->assertEquals(404, $client->getResponse()->getStatusCode());
        $this->assertTrue($crawler->filter('html:contains("ID -1not found!")')->count() > 0);
    }
}
