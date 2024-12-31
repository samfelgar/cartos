<?php

namespace Samfelgar\Cartos\Tests\CashIn\Models;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Samfelgar\Cartos\CashIn\Models\CashInRequest;

#[CoversClass(CashInRequest::class)]
class CashInRequestTest extends TestCase
{
    #[Test]
    public function itSerializesToJsonWithoutTxId(): void
    {
        $expiration = 60 * 60 * 24;
        $request = new CashInRequest(
            null,
            null,
            150,
            $expiration,
        );

        $expected = \json_encode([
            'valor' => 15000,
            'tempoExpiracao' => $expiration,
        ]);

        $this->assertJsonStringEqualsJsonString($expected, \json_encode($request));
    }

    #[Test]
    public function itSerializesToJsonWithTxId(): void
    {
        $id = 'laksdflakjsdflakjsdflkajsdf';
        $expiration = 60 * 60 * 24;
        $request = new CashInRequest(
            $id,
            null,
            150,
            $expiration,
        );

        $expected = \json_encode([
            'txId' => $id,
            'valor' => 15000,
            'tempoExpiracao' => $expiration,
        ]);

        $this->assertJsonStringEqualsJsonString($expected, \json_encode($request));
    }

    #[Test]
    public function itSerializesToJsonWithoutInformation(): void
    {
        $id = 'laksdflakjsdflakjsdflkajsdf';
        $expiration = 60 * 60 * 24;
        $request = new CashInRequest(
            $id,
            null,
            150,
            $expiration,
        );

        $expected = \json_encode([
            'txId' => $id,
            'valor' => 15000,
            'tempoExpiracao' => $expiration,
        ]);

        $this->assertJsonStringEqualsJsonString($expected, \json_encode($request));
    }

    #[Test]
    public function itSerializesToJsonWithInformation(): void
    {
        $id = 'laksdflakjsdflakjsdflkajsdf';
        $expiration = 60 * 60 * 24;
        $request = new CashInRequest(
            $id,
            'information',
            150,
            $expiration,
        );

        $expected = \json_encode([
            'txId' => $id,
            'valor' => 15000,
            'tempoExpiracao' => $expiration,
            'informacaoAdicional' => 'information',
        ]);

        $this->assertJsonStringEqualsJsonString($expected, \json_encode($request));
    }

    #[Test]
    #[DataProvider('itValidatesTheTxIdProvider')]
    public function itValidatesTheTxId(string $id): void
    {
        $expiration = 60 * 60 * 24;
        $this->expectException(\InvalidArgumentException::class);
        new CashInRequest(
            $id,
            null,
            150,
            $expiration,
        );
    }

    public static function itValidatesTheTxIdProvider(): array
    {
        return [
            ['laksjflkajsdjlfkja'],
            ['laksjflkajsdjlfkjalaksdkjfaoisdfjoaisdfi'],
            ['laksjflka-jsdjlfkjalak-sdkjfaoisdfjoaisdfi'],
        ];
    }
}
