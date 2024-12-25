<?php

declare(strict_types=1);

namespace Samfelgar\Cartos\CashOut\Models\Webhook;

enum Status: string
{
    case Processing = 'Em processamento';
    case Confirmed = 'Sucesso';
    case Fail = 'Falha';
    case Error = 'Erro';
}
