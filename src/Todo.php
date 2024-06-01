<?php

namespace Todo;

use Carbon\Carbon;

class Todo
{
    private int $id;
    private string $task;
    private string $status = 'pending';
    private ?Carbon $createdAt;
    private ?Carbon $completedAt = null;

    public function __construct(string $task, ?int $id = null, ?Carbon $createdAt = null)
    {
        $this->task = $task;
        $this->createdAt = $createdAt ?? Carbon::now();
        if ($id !== null) {
            $this->setId($id);
        }
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function markCompleted(): void
    {
        $this->status = 'completed';
        $this->completedAt = Carbon::now();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getTask(): string
    {
        return $this->task;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getCreatedAt(): ?Carbon
    {
        return $this->createdAt;
    }

    public function getCompletedAt(): ?Carbon
    {
        return $this->completedAt;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'task' => $this->task,
            'status' => $this->status,
            'createdAt' => $this->createdAt ? $this->createdAt->toDateTimeString() : null,
            'completedAt' => $this->completedAt ? $this->completedAt->toDateTimeString() : null
        ];
    }
}
