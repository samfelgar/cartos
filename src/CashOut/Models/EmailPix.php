<?php

declare(strict_types=1);

namespace Samfelgar\Cartos\CashOut\Models;

use Webmozart\Assert\Assert;

class EmailPix implements PixInterface
{
    private readonly string $email;

    public function __construct(
        string $email,
    ) {
        $email = \mb_convert_case($email, \MB_CASE_LOWER);
        Assert::email($email);
        $this->email = $email;
    }

    public function value(): string
    {
        return $this->email;
    }
}
