<?php

namespace App\Repository;

use App\Entity\Task;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use DateTime;
use DateInterval;

/**
 * @extends ServiceEntityRepository<Task>
 */
class TaskRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Task::class);
    }

    public function findAllOrdered() {
        $now = new DateTime();
        $tasks = $this->findAll();

        /**
         * We calcul here the next time that the task need to be done
         */
        foreach ($tasks as $task) {
            if ($task->getLastDone()) {
                $nextTimeTodo = (new DateTime($task->getLastDone()->format('Y-m-d')))->add(new DateInterval('P'. $task->getDelay() .'D'));
                $task->setNextTimeTodo($nextTimeTodo);
                $interval = $now->diff($nextTimeTodo);
                $task->setIsDeadlinePast($interval->invert == 1);
            } else {
                $task->setIsDeadlinePast(true);
            }
        }

        /**
         * The sort logic,
         * On the top, the task withat last done
         * Then the task with deadline in past, by order of least recent date
         * Then the task to come, by order of most close to come today
         */
        usort($tasks, function($taskA, $taskB) {
            if ($taskA->getIsDeadlinePast() && !$taskB->getIsDeadlinePast()) {
                return -1;
            }
            if (!$taskA->getIsDeadlinePast() && $taskB->getIsDeadlinePast()) {
                return 1;
            }
            if ($taskA->getIsDeadlinePast() && $taskB->getIsDeadlinePast()) {
                return $taskA->getNextTimeTodo() > $taskB->getNextTimeTodo() ? 1 : -1;
            }
            return $taskA->getNextTimeTodo() < $taskB->getNextTimeTodo() ? -1 : 1;
        });

        return $tasks;
    }


}
