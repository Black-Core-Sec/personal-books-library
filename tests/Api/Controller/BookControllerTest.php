<?php

namespace App\Tests\Api\Controller;

use App\DataFixtures\BookFixtures;
use App\Entity\Book;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BookControllerTest extends WebTestCase
{
    private $apiKey;
    private $client;

    protected function setUp(): void
    {
        $this->apiKey = static::bootKernel()->getContainer()->getParameter('api_key');
        $this->client = static::createClient();
        $this->client->followRedirects();
    }

    public function testIndex()
    {
        // Testing on success
        $this->client->request(
            'GET',
            '/api/v2/books',[],[],
            ['HTTP_X_AUTH_TOKEN' => $this->apiKey]
        );

        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertJson($this->client->getResponse()->getContent());

        // Testing on fail
        $this->client->request(
            'GET',
            '/api/v2/books'
        );

        $this->assertNotEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertJson($this->client->getResponse()->getContent());
    }

    public function testAdd()
    {
        $this->client->request(
            'POST',
            '/api/v2/books/add',
            [],
            [],
            ['HTTP_X_AUTH_TOKEN' => $this->apiKey],
            '{"name": "Война и Мир_3", "author": "Лев Толстой", "last_read_datetime": "03-02-2018T10:24:00", "is_downloadable": true}'
        );

        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertJson($this->client->getResponse()->getContent());
    }

    public function testEdit()
    {
        $kernel = self::bootKernel();
        $em = $kernel->getContainer()->get('doctrine.orm.entity_manager');
        $loader = new Loader();
        $loader->addFixture(new BookFixtures());
        $executor = new ORMExecutor($em, (new ORMPurger));
        $executor->execute($loader->getFixtures());
        /** @var Book $book */
        $book = $executor->getReferenceRepository()->getReference(BookFixtures::REFERENCE);

        $newName = 'New Book name';
        $this->client->request(
            'POST',
            '/api/v2/books/'. $book->getId() .'/edit',
            [],
            [],
            ['HTTP_X_AUTH_TOKEN' => $this->apiKey],
            '{"name": "'.$newName.'"}'
        );

        $this->assertEquals($newName, $book->getName());
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertJson($this->client->getResponse()->getContent());
    }
}
