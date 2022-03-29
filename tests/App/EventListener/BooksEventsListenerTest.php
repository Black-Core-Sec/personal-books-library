<?php
declare(strict_types=1);

namespace App\Tests\App\EventListener;

use App\Entity\Book;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class BooksEventsListenerTest extends KernelTestCase
{
    private $book;
    private $repository;
    private $filesPath;
    private $coversPath;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();
        $container = $kernel->getContainer();
        $this->repository = $container->get('doctrine')->getRepository(Book::class);

        $randomString = bin2hex(random_bytes(8));
        $this->filePath = $container->getParameter('bookAbsoluteFilesDirectory').$randomString.'.txt';
        $this->coverPath = $container->getParameter('bookAbsoluteCoversDirectory').$randomString.'.jpg';

        $this->book = new Book();
        $this->book->setName($randomString);
        $this->book->setAuthor($randomString);
        $this->book->setFile($randomString.'.txt');
        $this->book->setCover($randomString.'.jpg');
        $this->book->setLastReadDatetime(new \DateTime());
        $this->book->setIsDownloadable(true);

        $this->repository->add($this->book);

        file_put_contents($this->filePath, '');
        file_put_contents($this->coverPath, '');
    }

    protected function tearDown(): void
    {
        $this->repository->remove($this->book);
        !file_exists($this->filePath) ?: unlink($this->filePath);
        !file_exists($this->coverPath) ?: unlink($this->coverPath);
    }

    public function testPostRemoveBook(): void
    {
        $this->assertFileExists($this->filePath);
        $this->assertFileExists($this->coverPath);

        $this->repository->remove($this->book);

        $this->assertFileNotExists($this->filePath);
        $this->assertFileNotExists($this->coverPath);
    }
}
