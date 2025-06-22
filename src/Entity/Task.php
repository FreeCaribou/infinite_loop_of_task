<?php

namespace App\Entity;

use App\Repository\TaskRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TaskRepository::class)]
class Task
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $label = null;

    // In day, the delay before doing again this task
    #[ORM\Column]
    private ?int $delay = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $lastDone = null;

    private ?\DateTime $nextTimeTodo = null;

    private ?bool $isDeadlinePast = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): static
    {
        $this->label = $label;
        return $this;
    }

    public function getDelay(): ?int
    {
        return $this->delay;
    }

    public function setDelay(int $delay): static
    {
        $this->delay = $delay;
        return $this;
    }

    public function getLastDone(): ?\DateTime
    {
        return $this->lastDone;
    }

    public function setLastDone(?\DateTime $lastDone): static
    {
        $this->lastDone = $lastDone;
        return $this;
    }

    public function getNextTimeTodo(): ?\DateTime
    {
        return $this->nextTimeTodo;
    }

    public function setNextTimeTodo(?\DateTime $nextTimeTodo): static
    {
        $this->nextTimeTodo = $nextTimeTodo;
        return $this;
    }

    public function getIsDeadlinePast(): ?bool
    {
        return $this->isDeadlinePast;
    }

    public function setIsDeadlinePast(bool $isDeadlinePast): static
    {
        $this->isDeadlinePast = $isDeadlinePast;
        return $this;
    }

}
