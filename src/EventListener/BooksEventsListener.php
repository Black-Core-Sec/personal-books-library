<?php
declare(strict_types=1);

namespace App\EventListener;

use App\Entity\Book;
use App\Repository\BookRepository;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Contracts\Cache\CacheInterface;

class BooksEventsListener
{
    private $bookRepository, $booksCache;

    public function __construct(BookRepository $bookRepository, CacheInterface $booksCache)
    {
        $this->bookRepository = $bookRepository;
        $this->booksCache = $booksCache;
    }

    public function postPersist(Book $book, LifecycleEventArgs $event): void
    {
        $this->clearCache($this->bookRepository, $this->booksCache);
    }

    public function postUpdate(Book $book, LifecycleEventArgs $event): void
    {
        $this->clearCache($this->bookRepository, $this->booksCache);
    }

    public function postRemove(Book $book, LifecycleEventArgs $event): void
    {
        $this->clearCache($this->bookRepository, $this->booksCache);
    }


    /**
     * @param BookRepository $bookRepository
     * @param CacheInterface $booksCache
     * @throws \Psr\Cache\InvalidArgumentException
     */
    private function clearCache(BookRepository $bookRepository, CacheInterface $booksCache): void
    {
        $booksCache->delete($bookRepository::LIST_CACHE_KEY);
    }
}
