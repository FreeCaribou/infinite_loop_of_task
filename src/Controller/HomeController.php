<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\TaskRepository;
use Symfony\Component\HttpFoundation\Request;
use DateTime;
use DateInterval;


class HomeController extends AbstractController
{

    #[Route('/', name: 'home', methods: ['GET', 'POST'])]
    public function home(TaskRepository $repo): Response
    {
        $now = new DateTime();
        $tasks = $repo->findAll();
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

        return $this->render('home.html.twig', [
            'tasks' => $tasks,
            'now' => $now,
        ]);
    }

}