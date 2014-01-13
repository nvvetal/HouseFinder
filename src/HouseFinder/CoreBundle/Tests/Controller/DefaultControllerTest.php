<?php

namespace HouseFinder\CoreBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/route/Zhytomyr/Kyiv');
        $this->assertTrue($crawler->filter('html:contains("Status:OK")')->count() > 0);
    }
}
