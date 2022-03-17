<?php
declare(strict_types=1);

namespace App\Events;

use App\Entity\Book;
use Symfony\Contracts\EventDispatcher\Event;

class BookEvent extends Event
{
    public const REMOVED = 'book.removed';
    public const FILE_CHANGED = 'book.file_changed';
    public const COVER_CHANGED = 'book.cover_changed';

    private $book;

    public function __construct(Book $book)
    {
        $this->book = $book;
    }

    public function getBook(): Book
    {
        return $this->book;
    }
}
