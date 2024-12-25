<?php

namespace Samfelgar\Cartos\Tests\CashOut\Models;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Samfelgar\Cartos\CashOut\Models\PhonePix;
use PHPUnit\Framework\TestCase;

#[CoversClass(PhonePix::class)]
class PhonePixTest extends TestCase
{
    #[Test]
    #[DataProvider('itValidatesTheInformedPhoneNumberProvider')]
    public function itValidatesTheInformedPhoneNumber(string $phone): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid phone number');
        new PhonePix($phone);
    }

    public static function itValidatesTheInformedPhoneNumberProvider(): array
    {
        return [
            ['+1234'],
            ['1234'],
            ['+551234'],
        ];
    }

    #[Test]
    public function itCanFormatAPhoneUsingTheE164Format(): void
    {
        $phone = new PhonePix('6199999-9999');
        $this->assertEquals('+5561999999999', $phone->value());
    }
}
