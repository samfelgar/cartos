<?php

namespace Samfelgar\Cartos\Tests\CashOut\Models;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Samfelgar\Cartos\CashOut\Models\EmailPix;
use PHPUnit\Framework\TestCase;

#[CoversClass(EmailPix::class)]
class EmailPixTest extends TestCase
{
    #[Test]
    #[DataProvider('itConvertsEmailToLowerCaseProvider')]
    public function itConvertsEmailToLowerCase(string $email, string $expected): void
    {
        $pix = new EmailPix($email);
        $this->assertEquals($expected, $pix->value());
    }

    public static function itConvertsEmailToLowerCaseProvider(): array
    {
        return [
            ['JOHNDOE@example.com', 'johndoe@example.com'],
            ['JOHN_DOE@example.com', 'john_doe@example.com'],
            ['john_DOE@example.com', 'john_doe@example.com'],
        ];
    }

    #[Test]
    public function itValidatesTheInformedEmail(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new EmailPix('abc');
    }
}
