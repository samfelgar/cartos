<?php

declare(strict_types=1);

namespace Samfelgar\Cartos\CashOut\Models;

use Webmozart\Assert\Assert;

class CnpjPix implements PixInterface
{
    private string $cnpj;

    public function __construct(
        string $cnpj,
    ) {
        $cnpj = \preg_replace('/\D/', '', $cnpj);
        Assert::length($cnpj, 14);
        $this->cnpj = $cnpj;
    }

    public function value(): string
    {
        return $this->cnpj;
    }
}
