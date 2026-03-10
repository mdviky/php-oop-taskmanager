<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Request;
use App\Core\Response;
use App\Services\TaskService;

final class TaskController extends Controller
{
    public function __construct(
        private TaskService $service,
        Request $request,
        Response $response
    ) {
        parent::__construct($request, $response);
    }

    public function index(): string
    {
        $tasks = $this->service->listTasks();
        return $this->view('tasks/index', ['tasks' => $tasks]);
    }

    public function show(string $id): Response|string
    {
        $task = $this->service->getTask((int) $id);
        if (!$task) {
            return $this->response->setStatus(404)->setBody('Task not found');
        }

        return $this->view('tasks/show', ['task' => $task]);
    }

    public function create(): string
    {
        return $this->view('tasks/create', ['errors' => [], 'old' => []]);
    }

    public function store(): Response|string
    {
        $result = $this->service->createTask($this->request->all());
        if (!empty($result['errors'])) {
            if ($this->request->isAjax()) {
                return $this->response->json(['errors' => $result['errors']], 422);
            }
            return $this->view('tasks/create', ['errors' => $result['errors'], 'old' => $this->request->all()]);
        }

        if ($this->request->isAjax()) {
            return $this->response->json(['id' => $result['id']], 201);
        }
        return $this->redirect('/tasks?notice=created');
    }

    public function edit(string $id): Response|string
    {
        $task = $this->service->getTask((int) $id);
        if (!$task) {
            return $this->response->setStatus(404)->setBody('Task not found');
        }

        return $this->view('tasks/edit', ['task' => $task, 'errors' => []]);
    }

    public function update(string $id): Response|string
    {
        $result = $this->service->updateTask((int) $id, $this->request->all());
        if (!empty($result['errors'])) {
            if ($this->request->isAjax()) {
                return $this->response->json(['errors' => $result['errors']], 422);
            }
            $task = $this->service->getTask((int) $id);
            if (!$task) {
                return $this->response->setStatus(404)->setBody('Task not found');
            }
            return $this->view('tasks/edit', [
                'task' => $task,
                'errors' => $result['errors'],
            ]);
        }

        if ($this->request->isAjax()) {
            return $this->response->json(['id' => (int) $id], 200);
        }
        return $this->redirect('/tasks?notice=updated');
    }

    public function delete(string $id): Response
    {
        $this->service->deleteTask((int) $id);
        if ($this->request->isAjax()) {
            return $this->response->json(['deleted' => true]);
        }
        return $this->redirect('/tasks?notice=deleted');
    }
}
