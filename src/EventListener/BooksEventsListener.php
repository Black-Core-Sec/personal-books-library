<?php
declare(strict_types=1);

namespace App\EventListener;

use App\Entity\Book;
use App\Entity\BookCover;
use App\Entity\BookFile;
use App\Repository\BookRepository;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Contracts\Cache\CacheInterface;

class BooksEventsListener
{
    private $bookRepository;
    private $booksCache;
    private $bookCover;
    private $bookFile;

    public function __construct(BookRepository $bookRepository, CacheInterface $booksCache, BookCover $bookCover, BookFile $bookFile)
    {
        $this->bookRepository = $bookRepository;
        $this->booksCache = $booksCache;
        $this->bookCover = $bookCover;
        $this->bookFile = $bookFile;
    }

    public function postPersist(Book $book, LifecycleEventArgs $event): void
    {
        $this->clearCache($this->bookRepository, $this->booksCache);
    }

    public function postUpdate(Book $book, LifecycleEventArgs $event): void
    {
        $entity = $event->getEntity();
        $entityManager = $event->getEntityManager();

        // Revove Cover and File if it changed
        if ($entity instanceof Book) {
            $changeSet = $entityManager->getUnitOfWork()->getEntityChangeSet($entity);
            if (isset($changeSet['cover']) && $changeSet['cover'][0]) {
                $this->bookCover->remove($changeSet['cover'][0]);
            }
            if (isset($changeSet['file']) && $changeSet['file'][0]) {
                $this->bookFile->remove($changeSet['file'][0]);
            }
        }
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
