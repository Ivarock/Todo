<?php

require 'vendor/autoload.php';

use Todo\Todo;
use Todo\TodoList;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Output\ConsoleOutput;

$todoList = new TodoList('todos.json');

function displayTodos(TodoList $todoList): void
{
    $output = new ConsoleOutput();
    $table = new Table($output);
    $table->setHeaders(['ID', 'TASK', 'STATUS', 'CREATED AT', 'COMPLETED AT']);
    foreach ($todoList->getTodos() as $todo) {
        $table->addRow([
            $todo->getId(),
            $todo->getTask(),
            $todo->getStatus(),
            $todo->getCreatedAt() ? $todo->getCreatedAt()->toDateTimeString() : null,
            $todo->getCompletedAt() ? $todo->getCompletedAt()->toDateTimeString() : null
        ]);
    }
    $table->render();
}

while (true) {
    echo "What would you like to do?\n";
    echo "1. List all TODOs\n";
    echo "2. Create new TODO\n";
    echo "3. Mark TODO as completed\n";
    echo "4. Delete TODO\n";
    echo "5. Exit\n";
    $choice = (int)readline();
    switch ($choice) {
        case 1:
            displayTodos($todoList);
            break;
        case 2:
            $task = readline("Enter task: ");
            $todoList->addTodo(new Todo($task));
            echo "TODO created!\n";
            break;
        case 3:
            $id = readline("Enter ID of TODO to mark as completed: ");
            $todoList->markTodoCompleted($id);
            echo "TODO marked as completed!\n";
            break;
        case 4:
            $id = readline("Enter ID of TODO to delete: ");
            $todoList->deleteTodo($id);
            echo "TODO deleted!\n";
            break;
        case 5:
            echo "Goodbye!\n";
            exit;
        default:
            echo "Invalid choice\n";
            break;
    }
}
