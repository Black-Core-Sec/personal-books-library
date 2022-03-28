<?php
namespace App\DataFixtures;

use App\Entity\Book;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

class BookFixtures extends Fixture implements OrderedFixtureInterface
{
    const REFERENCE = 'book';

    public function load(ObjectManager $manager)
    {
        $book = (new Book())
            ->setName('TestBook')
            ->setAuthor('Author')
            ->setLastReadDatetime((new \DateTime()))
            ->setIsDownloadable(true);

        $manager->persist($book);
        $manager->flush();

        $this->addReference(self::REFERENCE, $book);
    }

    public function getOrder(): int
    {
        return 0;
    }
}

