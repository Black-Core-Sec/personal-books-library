<?php
declare(strict_types=1);

namespace App\Tests;

use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;

abstract class WebTestCaseWithFixture extends WebTestCase
{
    protected static function executeFixture(string $fixtureName): ORMExecutor
    {
        if (!class_exists($fixtureName)) {
            throw new \Exception("Not found fixture - {$fixtureName}!");
        }
        $entityManager = (self::bootKernel())->getContainer()
            ->get('doctrine.orm.entity_manager');
        $loader = new Loader();
        $loader->addFixture(new $fixtureName);
        $executor = new ORMExecutor($entityManager, (new ORMPurger));
        $executor->execute($loader->getFixtures());

        return $executor;
    }
}