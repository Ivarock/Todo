<?php

namespace Todo;

use Carbon\Carbon;

class TodoList
{
    private array $todos = [];
    private string $filePath;
    private int $nextId;

    public function __construct(string $filePath)
    {
        $this->filePath = $filePath;
        $this->load();
    }

    public function addTodo(Todo $todo): void
    {
        $todo->setId($this->nextId);
        $this->todos[$this->nextId] = $todo;
        $this->nextId++;
        $this->save();
    }

    public function getTodos(): array
    {
        return $this->todos;
    }

    public function markTodoCompleted(int $id): void
    {
        if (isset($this->todos[$id])) {
            $this->todos[$id]->markCompleted();
            $this->save();
        }
    }

    public function deleteTodo(int $id): void
    {
        if (isset($this->todos[$id])) {
            unset($this->todos[$id]);
            $this->save();
        }
    }

    private function save(): void
    {
        $toDodata = [
            'nextId' => $this->nextId,
            'todos' => array_map(fn($todo) => $todo->toArray(), $this->todos)
        ];
        file_put_contents($this->filePath, json_encode($toDodata));
    }

    private function load(): void
    {
        if (file_exists($this->filePath)) {
            $data = json_decode(file_get_contents($this->filePath), true);
            $this->nextId = $data['nextId'] ?? 1;
            foreach ($data['todos'] as $item) {
                $todo = new Todo(
                    $item['task'],
                    null,
                    isset($item['createdAt']) ? Carbon::parse($item['createdAt']) : null
                );
                $todo->setId($item['id']);
                if ($item['status'] === 'completed' && isset($item['completedAt'])) {
                    $todo->markCompleted();
                }
                $this->todos[$item['id']] = $todo;
            }
        }
        if (empty($this->todos)) {$this->nextId = 1;
        }
    }
}
