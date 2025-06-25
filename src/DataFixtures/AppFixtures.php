<?php

namespace App\DataFixtures;

use App\Entity\Task;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $task = new Task();
        $task->setLabel('Baignoire');
        $task->setDelay(14);
        $task->setLastDone(\DateTime::createFromFormat('Y-m-d H:i:s', '2024-06-22 10:00:00'));
        $manager->persist($task);

        $task = new Task();
        $task->setLabel('Draps');
        $task->setDelay(28);
        $task->setLastDone(\DateTime::createFromFormat('Y-m-d', '2025-06-03'));
        $manager->persist($task);

        $task = new Task();
        $task->setLabel('Vitres');
        $task->setDelay(90);
        $manager->persist($task);

        $manager->flush();
    }
}