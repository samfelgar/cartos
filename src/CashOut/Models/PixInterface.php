<?php

declare(strict_types=1);

namespace Samfelgar\Cartos\CashOut\Models;

interface PixInterface
{
    public function value(): string;
}
