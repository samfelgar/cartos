<?php

declare(strict_types=1);

namespace Samfelgar\Cartos\CashIn\Models;

readonly class QrCode
{
    public function __construct(
        public string $image,
        public string $emv,
    ) {}
}
