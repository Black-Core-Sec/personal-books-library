<?php
declare(strict_types=1);

namespace App\Repository;

use App\Entity\Book;
use App\Events\BookEvent;
use App\EventSubscriber\BooksEventsSubscriber;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Book|null find($id, $lockMode = null, $lockVersion = null)
 * @method Book|null findOneBy(array $criteria, array $orderBy = null)
 * @method Book[]    findAll()
 * @method Book[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BookRepository extends ServiceEntityRepository
{
    public const LIST_CACHE_KEY = 'books_list';

    private $dispatcher;

    public function __construct(ManagerRegistry $registry, EventDispatcher $dispatcher, BooksEventsSubscriber $booksEventsSubscriber)
    {
        parent::__construct($registry, Book::class);
        $this->dispatcher = $dispatcher;
        $this->dispatcher->addSubscriber($booksEventsSubscriber);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Book $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(Book $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
            $event = new BookEvent($entity);
            $this->dispatcher->dispatch($event, BookEvent::REMOVED);
        }
    }

    /**
     * @param bool $isAscSortingDirection
     * @return array
     */
    public function getAllSortingByLastReadDatetime(bool $isAscSortingDirection = true): array
    {
        return $this->createQueryBuilder('b')
            ->orderBy('b.last_read_datetime', $isAscSortingDirection?'ASC':'DESC')
            ->getQuery()
            ->getResult();
    }
}
