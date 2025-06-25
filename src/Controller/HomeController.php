<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\TaskRepository;
use Symfony\Component\HttpFoundation\Request;
use App\Form\TaskType;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Task;
use DateTime;

class HomeController extends AbstractController
{

    #[Route('/', name: 'home', methods: ['GET', 'POST'])]
    public function home(TaskRepository $repo, Request $request, EntityManagerInterface $entityManager): Response
    {
        $newTask = new Task();
        $form = $this->createForm(TaskType::class, $newTask);

        // Verification if we have a new one
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $formTask = $form->getData();
            $entityManager->persist($formTask);
            $entityManager->flush();
            $this->addFlash('success', 'Nouvelle tache ajoutée !');
            return $this->redirectToRoute('home');
        }

        $tasks = $repo->findAllOrdered();

        return $this->render('home.html.twig', [
            'tasks' => $tasks,
            'form' => $form,
        ]);
    }

    #[Route('/task-delete/{id}', name: 'task-delete')]
    public function taskDelete($id, TaskRepository $repo, EntityManagerInterface $entityManager): Response
    {
        $taskToDelete = $repo->find($id);
        $entityManager->remove($taskToDelete);
        $entityManager->flush();
        $this->addFlash('success', 'La tache a bien été supprimée !');
        return $this->redirectToRoute('home');
    }

    #[Route('/task-renew/{id}', name: 'task-renew')]
    public function taskRenew($id, TaskRepository $repo, EntityManagerInterface $entityManager): Response
    {
        $taskToRenew = $repo->find($id);
        $taskToRenew->setLastDone(new DateTime());
        $entityManager->flush();
        $this->addFlash('success', 'Bravo d\'avoir fait la tache !');
        return $this->redirectToRoute('home');
    }

}