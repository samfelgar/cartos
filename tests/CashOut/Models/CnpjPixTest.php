<?php

namespace Samfelgar\Cartos\Tests\CashOut\Models;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Samfelgar\Cartos\CashOut\Models\CnpjPix;

#[CoversClass(CnpjPix::class)]
class CnpjPixTest extends TestCase
{
    #[Test]
    public function itThrowAnExceptionIfInvalidLength(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new CnpjPix('14875245000');
    }

    #[Test]
    #[DataProvider('cnpjProvider')]
    public function itStripsNonNumericalChars(string $cnpj, string $expected): void
    {
        $pix = new CnpjPix($cnpj);
        $this->assertEquals($expected, $pix->value());
    }

    public static function cnpjProvider(): array
    {
        return [
            ['50.924.029/0001-38', '50924029000138'],
            ['31.144.322/0001-38', '31144322000138'],
            ['19.743.028/0001-47', '19743028000147'],
            ['14.875.245/0001-77', '14875245000177'],
        ];
    }
}
