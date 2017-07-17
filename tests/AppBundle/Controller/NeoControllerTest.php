<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class NeoControllerTest extends WebTestCase
{
    public function testHazardous()
    {
        $client = static::createClient();

        $client->request('GET', '/neo/hazardous');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $json = $client->getResponse()->getContent();
        $this->assertJson($json);

        $data = json_decode($json);
        foreach ($data as $item) {
            $this->assertTrue($item->hazardous);
        }
    }

    public function testFastest()
    {
        $client    = static::createClient();
        $container = self::$kernel->getContainer();
        $dm        = $container->get('doctrine.odm.mongodb.document_manager');


        $client->request('GET', '/neo/fastest');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $json = $client->getResponse()->getContent();
        $this->assertJson($json);

        $data = json_decode($json);
        $this->assertCount(1, $data);
        $this->assertFalse($data[0]->hazardous);
        $filter          = [
            'speed'     => ['$gt' => $data[0]->speed],
            'hazardous' => false,
        ];
        $fasterAsteroids = $dm->getRepository('AppBundle:Asteroid')->findBy($filter);
        $this->assertCount(0, $fasterAsteroids);


        $client->request('GET', '/neo/fastest?hazardous=true');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $json = $client->getResponse()->getContent();
        $this->assertJson($json);

        $data = json_decode($json);
        $this->assertCount(1, $data);
        $this->assertTrue($data[0]->hazardous);

        $filter          = [
            'speed'     => ['$gt' => $data[0]->speed],
            'hazardous' => true,
        ];
        $fasterAsteroids = $dm
            ->getRepository('AppBundle:Asteroid')
            ->findBy($filter);
        $this->assertCount(0, $fasterAsteroids);
    }

    public function testBestYear()
    {
        $client    = static::createClient();
        $container = self::$kernel->getContainer();
        $dm        = $container->get('doctrine.odm.mongodb.document_manager');

        $client->request('GET', '/neo/best-year');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $json = $client->getResponse()->getContent();
        $this->assertJson($json);

        $data = json_decode($json, true);
        $this->assertNotEmpty($data);

        $nextYear = $data['year'] + 1;
        $count    = $dm
            ->getRepository('AppBundle:Asteroid')
            ->createQueryBuilder()
            ->field('hazardous')->equals(false)
            ->field('date')->gte(new \MongoDate(strtotime("first day of january $data[year]")))
            ->field('date')->lt(new \MongoDate(strtotime("first day of january $nextYear")))
            ->getQuery()
            ->execute();
        $this->assertCount($data['asteroids'], $count);

        // checking for hazardous asteroids
        $client->request('GET', '/neo/best-year?hazardous=true');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $json = $client->getResponse()->getContent();
        $this->assertJson($json);

        $data = json_decode($json, true);
        $this->assertNotEmpty($data);

        $nextYear = $data['year'] + 1;
        $count    = $dm
            ->getRepository('AppBundle:Asteroid')
            ->createQueryBuilder()
            ->field('hazardous')->equals(true)
            ->field('date')->gte(new \MongoDate(strtotime("first day of january $data[year]")))
            ->field('date')->lt(new \MongoDate(strtotime("first day of january $nextYear")))
            ->getQuery()
            ->execute();
        $this->assertCount($data['asteroids'], $count);
    }

    public function testBestMonth()
    {
        $client = static::createClient();

        $client->request('GET', '/neo/best-month');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $json = $client->getResponse()->getContent();
        $this->assertJson($json);

        $data = json_decode($json, true);
        $this->assertNotEmpty($data);

        // checking for hazardous asteroids
        $client->request('GET', '/neo/best-year?hazardous=true');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $json = $client->getResponse()->getContent();
        $this->assertJson($json);

        $data = json_decode($json, true);
        $this->assertNotEmpty($data);
    }

    public function testBestMonthYear()
    {
        $client = static::createClient();

        $client->request('GET', '/neo/best-month-year');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $json = $client->getResponse()->getContent();
        $this->assertJson($json);

        $data = json_decode($json, true);
        $this->assertNotEmpty($data);

        // checking for hazardous asteroids
        $client->request('GET', '/neo/best-month-year?hazardous=true');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $json = $client->getResponse()->getContent();
        $this->assertJson($json);

        $data = json_decode($json, true);
        $this->assertNotEmpty($data);
    }
}
