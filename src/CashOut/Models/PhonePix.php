<?php

declare(strict_types=1);

namespace Samfelgar\Cartos\CashOut\Models;

use Brick\PhoneNumber\PhoneNumber;
use Brick\PhoneNumber\PhoneNumberFormat;
use Brick\PhoneNumber\PhoneNumberParseException;

class PhonePix implements PixInterface
{
    private readonly PhoneNumber $phone;

    public function __construct(
        string $phone,
    ) {
        try {
            $this->phone = \str_starts_with($phone, '+')
                ? PhoneNumber::parse($phone)
                : PhoneNumber::parse($phone, 'BR');
        } catch (PhoneNumberParseException $e) {
            throw new \InvalidArgumentException('Invalid phone format', previous: $e);
        }

        if (!$this->phone->isPossibleNumber()) {
            throw new \InvalidArgumentException('Invalid phone number');
        }
    }

    public function value(): string
    {
        return $this->phone->format(PhoneNumberFormat::E164);
    }
}
