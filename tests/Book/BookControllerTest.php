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
            return $addition[0];
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
//        $date,
        $is_downloadable,
        $expected
    )
    {
        $kernel = self::bootKernel();
        $container = $kernel->getContainer();
        $repository = $container->get('doctrine')->getRepository(Book::class);

        $client = static::createClient();
        $client->followRedirects();

        $client->request('GET', '/book/new');
        $this->assertSelectorExists('input#username');
        $client->submitForm('_submit', [
            '_username' => 'user',
            '_password' => '123'
        ]);
        $this->assertSelectorTextContains('h1', 'Create new Book');

        $client->submitForm('Save', [
            'book[name]'    => $name,
            'book[author]'  => $author,
            'book[file]'    => $file,
            'book[cover]'   => $cover,
//            'book[last_read_datetime]'  => $date,
            'book[is_downloadable]'     => $is_downloadable
        ]);

        $is_saved = $repository->findOneBy(['name' => $name, 'author' => $author]) !== null;
        if ($expected) {
            $this->assertTrue($is_saved);
        } else {
            $this->assertFalse($is_saved);
        }
    }

    public function additionProvider()
    {
        return [
            [
                'Test2',
                'Test2',
                '',
                '',
//                [
//                    'date' => [
//                        'day'   => '01',
//                        'month' => '01',
//                        'year'  => '2017',
//                        'hour'  => '10',
//                        'minute'=> '30'
//                    ]
//                ],
                '1',
                true
            ],
            [
                'Test2',
                'Test2',
                '',
                '',
                '1',
                true
            ],
            [
                'Test3',
                'Test2',
                '',
                '',
                '1',
                true
            ],
        ];
    }
}
