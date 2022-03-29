<?php

namespace App\Tests\Api\Controller;

use App\Entity\Book;
use App\DataFixtures\BookFixtures;
use App\Tests\WebTestCaseWithFixture;
use Symfony\Component\HttpFoundation\Response;

class BookControllerTest extends WebTestCaseWithFixture
{
    private $apiKey;
    private $client;

    protected function setUp(): void
    {
        $this->apiKey = ((self::bootKernel())->getContainer())->getParameter('api_key');
        $this->client = static::createClient();
        $this->client->followRedirects();
    }

    public function testIndex(): void
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

        $this->assertEquals(Response::HTTP_UNAUTHORIZED, $this->client->getResponse()->getStatusCode());
        $this->assertJson($this->client->getResponse()->getContent());
    }

    public function testAdd(): void
    {
        $this->client->request(
            'POST',
            '/api/v2/books/add',
            [],
            [],
            ['HTTP_X_AUTH_TOKEN' => $this->apiKey],
            '{"name": "Война и Мир", "author": "Лев Толстой", "last_read_datetime": "03-02-2018T10:24:00", "is_downloadable": true}'
        );

        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertJson($this->client->getResponse()->getContent());
    }

    public function testEdit(): void
    {
        $executor = self::executeFixture(BookFixtures::class);

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
