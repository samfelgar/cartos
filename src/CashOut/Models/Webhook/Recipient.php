<?php

declare(strict_types=1);

namespace Samfelgar\Cartos\CashOut\Models\Webhook;

readonly class Recipient
{
    public function __construct(
        public ?string $name,
        public string $bankCode,
        public string $cpfCnpj,
    ) {}
}
