<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Controllers\TaskController;
use App\Core\Request;
use App\Core\Response;
use App\Core\View;
use App\Repositories\TaskRepository;
use App\Services\TaskService;
use Tests\Support\DatabaseTestCase;

final class TaskControllerTest extends DatabaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        View::setBasePath(__DIR__ . '/../../app/Views');
    }

    public function testIndexRendersTasks(): void
    {
        ['service' => $service] = $this->makeServiceWithTask('My Task');
        $controller = new TaskController($service, new Request('GET', '/tasks', [], []), new Response());

        $html = $controller->index();

        $this->assertStringContainsString('My Task', $html);
    }

    public function testShowReturnsNotFound(): void
    {
        $service = new TaskService(new TaskRepository($this->pdo));
        $response = new Response();
        $controller = new TaskController($service, new Request('GET', '/tasks/999', [], []), $response);

        $result = $controller->show('999');

        $this->assertInstanceOf(Response::class, $result);
        $this->assertSame('Task not found', $response->getBody());
        $this->assertSame(404, $this->getPrivateProperty($response, 'status'));
    }

    public function testStoreValidationErrorRendersForm(): void
    {
        $service = new TaskService(new TaskRepository($this->pdo));
        $request = new Request('POST', '/tasks', [], ['title' => '']);
        $controller = new TaskController($service, $request, new Response());

        $html = $controller->store();

        $this->assertStringContainsString('Title is required.', $html);
    }

    public function testStoreSuccessRedirects(): void
    {
        $service = new TaskService(new TaskRepository($this->pdo));
        $request = new Request('POST', '/tasks', [], ['title' => 'New Task']);
        $response = new Response();
        $controller = new TaskController($service, $request, $response);

        $result = $controller->store();

        $this->assertInstanceOf(Response::class, $result);
        $this->assertSame(302, $this->getPrivateProperty($response, 'status'));
        $this->assertSame('/tasks?notice=created', $this->getPrivateProperty($response, 'headers')['Location']);
    }

    public function testUpdateValidationErrorRendersForm(): void
    {
        ['service' => $service, 'id' => $id] = $this->makeServiceWithTask('Existing');
        $request = new Request('POST', "/tasks/{$id}", [], ['title' => '']);
        $controller = new TaskController($service, $request, new Response());

        $html = $controller->update((string) $id);

        $this->assertStringContainsString('Title is required.', $html);
    }

    public function testUpdateSuccessRedirects(): void
    {
        ['service' => $service, 'id' => $id] = $this->makeServiceWithTask('Existing');
        $request = new Request('POST', "/tasks/{$id}", [], ['title' => 'Updated']);
        $response = new Response();
        $controller = new TaskController($service, $request, $response);

        $result = $controller->update((string) $id);

        $this->assertInstanceOf(Response::class, $result);
        $this->assertSame(302, $this->getPrivateProperty($response, 'status'));
        $this->assertSame('/tasks?notice=updated', $this->getPrivateProperty($response, 'headers')['Location']);
    }

    public function testDeleteRedirects(): void
    {
        ['service' => $service, 'id' => $id] = $this->makeServiceWithTask('To delete');
        $response = new Response();
        $controller = new TaskController($service, new Request('POST', "/tasks/{$id}/delete", [], []), $response);

        $result = $controller->delete((string) $id);

        $this->assertInstanceOf(Response::class, $result);
        $this->assertSame(302, $this->getPrivateProperty($response, 'status'));
    }

    private function makeServiceWithTask(string $title): array
    {
        $repo = new TaskRepository($this->pdo);
        $service = new TaskService($repo);
        $created = $service->createTask(['title' => $title]);

        return [
            'service' => $service,
            'id' => $created['id'],
        ];
    }

    private function getPrivateProperty(object $object, string $property): mixed
    {
        $ref = new \ReflectionProperty($object, $property);
        $ref->setAccessible(true);
        return $ref->getValue($object);
    }

/*     public function testStoreAjaxSuccessReturnsJson(): void
    {
        $_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';

        $service = new TaskService(new TaskRepository($this->pdo));
        $request = new Request('POST', '/tasks', [], ['title' => 'Ajax Task']);
        $response = new Response();
        $controller = new TaskController($service, $request, $response);

        $result = $controller->store();

        $this->assertInstanceOf(Response::class, $result);
        $this->assertSame(201, $this->getPrivateProperty($response, 'status'));

        $decoded = json_decode($response->getBody(), true);
        $this->assertIsArray($decoded);
        $this->assertArrayHasKey('id', $decoded);

        unset($_SERVER['HTTP_X_REQUESTED_WITH']);
    }

    public function testStoreAjaxValidationReturns422(): void
    {
        $_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';

        $service = new TaskService(new TaskRepository($this->pdo));
        $request = new Request('POST', '/tasks', [], ['title' => '']);
        $response = new Response();
        $controller = new TaskController($service, $request, $response);

        $result = $controller->store();

        $this->assertSame(422, $this->getPrivateProperty($response, 'status'));

        $decoded = json_decode($response->getBody(), true);
        $this->assertArrayHasKey('errors', $decoded);

        unset($_SERVER['HTTP_X_REQUESTED_WITH']);
    } */

        public function testStoreAjaxSuccessReturnsJson(): void
{
    $service = new TaskService(new TaskRepository($this->pdo));

    $request = new Request(
        'POST',
        '/tasks',
        [],
        ['title' => 'Ajax Task'],
        ['HTTP_X_REQUESTED_WITH' => 'XMLHttpRequest']
    );

    $response = new Response();
    $controller = new TaskController($service, $request, $response);

    $result = $controller->store();

    $this->assertInstanceOf(Response::class, $result);
    $this->assertSame(201, $this->getPrivateProperty($response, 'status'));

    $decoded = json_decode($response->getBody(), true);
    $this->assertIsArray($decoded);
    $this->assertArrayHasKey('id', $decoded);
}
}
