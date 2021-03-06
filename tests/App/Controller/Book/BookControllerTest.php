<?php
declare(strict_types=1);

namespace App\Tests\App\Controller\Book;

use App\Entity\Book;
use App\DataFixtures\UserFixtures;
use App\Tests\WebTestCaseWithFixture;

class BookControllerTest extends WebTestCaseWithFixture
{
    public static function setUpBeforeClass(): void
    {
        self::executeFixture(UserFixtures::class);
    }

    public static function tearDownAfterClass(): void
    {
        $repository = (self::bootKernel())->getContainer()
            ->get('doctrine')->getRepository(Book::class);

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
        string $name,
        string $author,
        bool $file,
        bool $cover,
        array $datetime,
        bool $is_downloadable,
        bool $expected
    ): void
    {
        $fileExamplesPath = (self::bootKernel())->getProjectDir() . '/tests/FileExamples/';

        $client = static::createClient();
        $client->followRedirects();

        $client->request('GET', '/book/new');
        $this->assertSelectorExists('input#username');

        $client->submitForm('_submit', [
            '_username' => UserFixtures::USERNAME,
            '_password' => UserFixtures::PASSWORD
        ]);
        $this->assertSelectorTextContains('h1', 'Create new Book');

        $crawler = $client->request('GET', '/book/new');

        $form = $crawler->selectButton('Save')->form();
        $form['book[name]']    = $name;
        $form['book[author]']  = $author;
        !$file ?: $form['book[file]']->upload($fileExamplesPath.'example.txt');
        !$cover ?: $form['book[cover]']->upload($fileExamplesPath.'example.jpeg');
        $form['book[last_read_datetime]'] = $datetime;
        $form['book[is_downloadable]']     = $is_downloadable;

        $crawler = $client->submit($form);

        if ($expected) {
            $this->assertSelectorTextContains('h1', 'Book index');
        } else {
            $this->assertSelectorTextContains('h1', 'Create new Book');
        }
    }

    public function additionProvider(): array
    {
        return [
            [
                'name' => 'Test1',
                'author' => 'Test1',
                'file' => true,
                'cover' => false,
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
                'is_downloadable' => true,
                'expected' => true
            ],
            [
                'name' => 'Test1',
                'author' => 'Test1',
                'file' => false,
                'cover' => false,
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
                'is_downloadable' => true,
                'expected' => false
            ],
            [
                'name' => 'Test2',
                'author' => 'Test1',
                'file' => true,
                'cover' => true,
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
                'is_downloadable' => false,
                'expected' => true
            ],
        ];
    }
}
