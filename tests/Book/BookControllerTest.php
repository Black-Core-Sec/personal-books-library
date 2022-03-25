<?php

namespace App\Tests\Book;

use App\Controller\BookController;
use App\Entity\Book;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BookControllerTest extends WebTestCase
{
    public static function tearDownAfterClass(): void
    {
        $kernel = self::bootKernel();
        $container = $kernel->getContainer();
        $repository = $container->get('doctrine')->getRepository(Book::class);

        $additions = (new self)->additionProvider();
        $testBooksNames = array_map(function ($addition) {
            return $addition['name'];
        }, $additions);

        $testBooks = $repository->findBy(
            ['name' => $testBooksNames]
        );

        foreach ($testBooks as $testBook) {
            $repository->remove($testBook);
        }
    }

    /**
     * @dataProvider additionProvider
     */
    public function testNewBook(
        $name,
        $author,
        $file,
        $cover,
        $datetime,
        $is_downloadable,
        $expected
    )
    {
        $client = static::createClient();
        $client->followRedirects();

        $client->request('GET', '/book/new');
        $this->assertSelectorExists('input#username');

        $client->submitForm('_submit', [
            '_username' => 'user',
            '_password' => '123'
        ]);
        $this->assertSelectorTextContains('h1', 'Create new Book');

        $crawler = $client->request('GET', '/book/new');

        $form = $crawler->selectButton('Save')->form();
        $form['book[name]']    = $name;
        $form['book[author]']  = $author;
        $form['book[file]']    = $file;
        $form['book[cover]']   = $cover;
        $form['book[last_read_datetime]'] = $datetime;
        $form['book[is_downloadable]']     = $is_downloadable;

        $crawler = $client->submit($form);

        if ($expected) {
            $this->assertSelectorTextContains('h1', 'Book index');
        } else {
            $this->assertSelectorTextContains('h1', 'Create new Book');
        }
    }

    public function additionProvider()
    {
        return [
            [
                'name' => 'Test1',
                'author' => 'Test1',
                'file' => '',
                'cover' => '',
                'last_read_datetime' => [
                    'date' => [
                        'day'   => '1',
                        'month' => '2',
                        'year'  => '2017'
                    ],
                    'time' => [
                        'hour'  => '13',
                        'minute'=> '40'
                    ]
                 ],
                'is_downloadable' => '1',
                'expected' => true
            ],
            [
                'name' => 'Test1',
                'author' => 'Test1',
                'file' => '',
                'cover' => '',
                'last_read_datetime' => [
                    'date' => [
                        'day'   => '1',
                        'month' => '2',
                        'year'  => '2017'
                    ],
                    'time' => [
                        'hour'  => '13',
                        'minute'=> '40'
                    ]
                ],
                'is_downloadable' => '1',
                'expected' => false
            ],
            [
                'name' => 'Test2',
                'author' => 'Test1',
                'file' => '',
                'cover' => '',
                'last_read_datetime' => [
                    'date' => [
                        'day'   => '1',
                        'month' => '2',
                        'year'  => '2018'
                    ],
                    'time' => [
                        'hour'  => '10',
                        'minute'=> '30'
                    ]
                ],
                'is_downloadable' => '1',
                'expected' => true
            ],
        ];
    }
}