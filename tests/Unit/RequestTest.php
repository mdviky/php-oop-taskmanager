<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Core\Request;
use PHPUnit\Framework\TestCase;

final class RequestTest extends TestCase
{
    public function testItReadsInputAndQuery(): void
    {
        $request = new Request('POST', '/tasks', ['q' => 'x'], ['title' => 'Hello']);

        $this->assertSame('POST', $request->method());
        $this->assertSame('/tasks', $request->path());
        $this->assertSame('Hello', $request->input('title'));
        $this->assertSame('x', $request->query('q'));
        $this->assertSame(['title' => 'Hello'], $request->all());
    }

    public function testFromGlobals(): void
    {
        $server = $_SERVER;
        $get = $_GET;
        $post = $_POST;

        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['REQUEST_URI'] = '/tasks/123?x=1';
        $_GET = ['x' => '1'];
        $_POST = [];

        $request = Request::fromGlobals();

        $this->assertSame('GET', $request->method());
        $this->assertSame('/tasks/123', $request->path());
        $this->assertSame('1', $request->query('x'));

        $_SERVER = $server;
        $_GET = $get;
        $_POST = $post;
    }
}
