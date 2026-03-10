<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Core\Response;
use PHPUnit\Framework\TestCase;

final class ResponseTest extends TestCase
{
    public function testItSetsStatusHeadersAndBody(): void
    {
        $response = new Response();
        $response->setStatus(201)->setHeader('X-Test', '1')->setBody('ok');

        $this->assertSame('ok', $response->getBody());

        $status = $this->getPrivateProperty($response, 'status');
        $headers = $this->getPrivateProperty($response, 'headers');

        $this->assertSame(201, $status);
        $this->assertSame(['X-Test' => '1'], $headers);
    }

    private function getPrivateProperty(object $object, string $property): mixed
    {
        $ref = new \ReflectionProperty($object, $property);
        $ref->setAccessible(true);
        return $ref->getValue($object);
    }
}
