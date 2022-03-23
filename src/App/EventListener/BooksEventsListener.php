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
    private $booksCache;
    private $bookCover;
    private $bookFile;

    public function __construct(CacheInterface $booksCache, BookCover $bookCover, BookFile $bookFile)
    {
        $this->booksCache = $booksCache;
        $this->bookCover = $bookCover;
        $this->bookFile = $bookFile;
    }

    public function postPersist(Book $book, LifecycleEventArgs $event): void
    {
        $this->clearCache();
    }

    public function postUpdate(Book $book, LifecycleEventArgs $event): void
    {
        $entity = $event->getEntity();
        $entityManager = $event->getEntityManager();

        // Revove Cover and File if it changed
        $changeSet = $entityManager->getUnitOfWork()->getEntityChangeSet($entity);
        if (isset($changeSet['cover']) && $changeSet['cover'][0]) {
            $this->bookCover->remove($changeSet['cover'][0]);
        }
        if (isset($changeSet['file']) && $changeSet['file'][0]) {
            $this->bookFile->remove($changeSet['file'][0]);
        }

        $this->clearCache();
    }

    public function postRemove(Book $book, LifecycleEventArgs $event): void
    {
        !$book->getFile() ?: $this->bookFile->remove($book->getFile());
        !$book->getCover() ?: $this->bookCover->remove($book->getCover());
        $this->clearCache();
    }

    /**
     * @throws \Psr\Cache\InvalidArgumentException
     */
    private function clearCache(): void
    {
        $this->booksCache->delete(BookRepository::LIST_CACHE_KEY);
    }
}
