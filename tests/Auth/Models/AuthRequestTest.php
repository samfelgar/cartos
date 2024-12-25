<?php

namespace Samfelgar\Cartos\Tests\Auth\Models;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Samfelgar\Cartos\Auth\Models\AuthRequest;
use PHPUnit\Framework\TestCase;

#[CoversClass(AuthRequest::class)]
class AuthRequestTest extends TestCase
{
    #[Test]
    public function itSerializesToJson(): void
    {
        $expected = \json_encode([
            'clientId' => 'asdf',
            'clientSecret' => 'asdf',
        ]);

        $request = new AuthRequest('asdf', 'asdf');
        $this->assertJsonStringEqualsJsonString($expected, \json_encode($request));
    }
}
