<?php

declare(strict_types=1);

namespace Samfelgar\Cartos\CashOut\Models\Webhook;

enum Event: string
{
    case PixOut = 'PixOut';
    case PixOutExternalReversal = 'PixOutReversalExternal';
}
