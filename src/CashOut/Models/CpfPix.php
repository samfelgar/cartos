<?php

declare(strict_types=1);

namespace Samfelgar\Cartos\CashOut\Models;

use Webmozart\Assert\Assert;

class CpfPix implements PixInterface
{
    private string $cpf;

    public function __construct(
        string $cpf,
    ) {
        $cpf = \preg_replace('/\D/', '', $cpf);
        Assert::length($cpf, 11);
        $this->cpf = $cpf;
    }

    public function value(): string
    {
        return $this->cpf;
    }
}
