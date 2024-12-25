<?php

namespace Samfelgar\Cartos\Tests\CashOut\Models;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use Samfelgar\Cartos\CashOut\Models\CashOutRequest;
use Samfelgar\Cartos\CashOut\Models\EmailPix;

#[CoversClass(CashOutRequest::class)]
#[UsesClass(EmailPix::class)]
class CashOutRequestTest extends TestCase
{
    #[Test]
    public function itSerializesToJsonWithoutDescription(): void
    {
        $id = \uniqid();

        $request = new CashOutRequest(
            $id,
            150.0,
            new EmailPix('johndoe@example.com'),
        );

        $expected = \json_encode([
            'idEnvio' => $id,
            'valor' => 15000,
            'chavePixDestino' => 'johndoe@example.com',
        ]);

        $this->assertEquals($expected, \json_encode($request));
    }

    #[Test]
    public function itSerializesToJsonWithDescription(): void
    {
        $id = \uniqid();

        $request = new CashOutRequest(
            $id,
            150.0,
            new EmailPix('johndoe@example.com'),
            'asdf',
        );

        $expected = \json_encode([
            'idEnvio' => $id,
            'valor' => 15000,
            'chavePixDestino' => 'johndoe@example.com',
            'descricao' => 'asdf',
        ]);

        $this->assertEquals($expected, \json_encode($request));
    }

    #[Test]
    #[DataProvider('itValidatesTheIdLengthProvider')]
    public function itValidatesTheIdLength(string $id): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new CashOutRequest(
            $id,
            150.0,
            new EmailPix('johndoe@example.com'),
        );
    }

    public static function itValidatesTheIdLengthProvider(): array
    {
        return [
            [''],
            ['asdlkfalksjdfalksflaksjaoiwoierlksdjfqoiwoeiuroi28389249'],
        ];
    }

    #[Test]
    public function itValidatesTheAmountToBeGreaterThanZero(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new CashOutRequest(
            \uniqid(),
            -10,
            new EmailPix('johndoe@example.com'),
        );
    }

    #[Test]
    public function itValidatesTheAmountToBeLessThanTwentyThousands(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new CashOutRequest(
            \uniqid(),
            20_001,
            new EmailPix('johndoe@example.com'),
        );
    }
}
