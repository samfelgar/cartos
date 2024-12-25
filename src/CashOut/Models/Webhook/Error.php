<?php

declare(strict_types=1);

namespace Samfelgar\Cartos\CashOut\Models\Webhook;

readonly class Error
{
    public function __construct(
        public ?string $origin,
        public ?string $reason,
        public ?string $message,
    ) {}
}
