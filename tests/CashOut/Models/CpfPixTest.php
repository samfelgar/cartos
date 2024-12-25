<?php

namespace Samfelgar\Cartos\Tests\CashOut\Models;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Samfelgar\Cartos\CashOut\Models\CpfPix;
use PHPUnit\Framework\TestCase;

#[CoversClass(CpfPix::class)]
class CpfPixTest extends TestCase
{
    #[Test]
    public function itThrowAnExceptionIfInvalidLength(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new CpfPix('50924029000138');
    }

    #[Test]
    #[DataProvider('cpfProvider')]
    public function itStripsNonNumericalChars(string $cpf, string $expected): void
    {
        $pix = new CpfPix($cpf);
        $this->assertEquals($expected, $pix->value());
    }

    public static function cpfProvider(): array
    {
        return [
            ['538.239.950-65', '53823995065'],
            ['599.233.630-31', '59923363031'],
            ['264.144.790-80', '26414479080'],
            ['219.784.600-07', '21978460007'],
        ];
    }
}
