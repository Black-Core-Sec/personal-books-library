<?php
declare(strict_types=1);

namespace App\EventSubscriber;

use App\Entity\BookCover;
use App\Entity\BookFile;
use App\Events\BookEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class BooksEventsSubscriber implements EventSubscriberInterface
{
    private $bookCover;
    private $bookFile;

    public function __construct(BookFile $bookFile, BookCover $bookCover)
    {
        $this->bookFile = $bookFile;
        $this->bookCover = $bookCover;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            BookEvent::REMOVED => ['onBookRemoved', 10],
        ];
    }

    public function onBookRemoved(BookEvent $event)
    {
        $this->bookCover->remove($event->getBook()->getCover());
        $this->bookFile->remove($event->getBook()->getFile());
    }
}
