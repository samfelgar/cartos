<?php

declare(strict_types=1);

namespace Samfelgar\Cartos\CashIn\Models\Webhook;

readonly class Payer
{
    public function __construct(
        public string $name,
        public string $cpfCnpj,
        public string $bankCode,
    ) {}
}
