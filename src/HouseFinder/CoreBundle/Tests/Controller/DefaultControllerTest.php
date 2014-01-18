<?php

namespace HouseFinder\CoreBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/route/Житомир,%20пл.%20Соборная,%201/Житомир,%20ул.%20Чапаева,%207');
        $this->assertTrue($crawler->filter('html:contains("Status:OK")')->count() > 0);
    }
}
