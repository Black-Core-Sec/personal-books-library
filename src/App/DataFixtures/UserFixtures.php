<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

class UserFixtures extends Fixture implements OrderedFixtureInterface
{
    public const REFERENCE = 'user';
    public const USERNAME  = 'testUser';
    public const PASSWORD  = 'testPassword';
    private const EMAIL    = 'test@mail.net';

    public function load(ObjectManager $manager): void
    {
        $user = (new User())
            ->setUsername(self::USERNAME)
            ->setEmail(self::EMAIL)
            ->setPlainPassword(self::PASSWORD)
            ->setEnabled(true)
            ->setRoles([User::ROLE_DEFAULT]);

        $manager->persist($user);
        $manager->flush();

        $this->addReference(self::REFERENCE, $user);
    }

    public function getOrder(): int
    {
        return 0;
    }
}
