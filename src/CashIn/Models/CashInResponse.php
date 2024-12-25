<?php

declare(strict_types=1);

namespace Samfelgar\Cartos\CashIn\Models;

use Psr\Http\Message\ResponseInterface;

class CashInResponse
{
    public function __construct(
        public readonly bool $success,
        public readonly string $message,
        public readonly string $txId,
        public readonly QrCode $qrCode,
    ) {}

    public static function fromArray(array $data): CashInResponse
    {
        return new CashInResponse(
            (bool)$data['sucesso'],
            $data['mensagem'],
            $data['txId'],
            new QrCode(
                $data['qrcode']['Imagem'],
                $data['qrcode']['EMV'],
            ),
        );
    }

    public static function fromResponse(ResponseInterface $response): CashInResponse
    {
        $body = \json_decode((string)$response->getBody(), true);
        return self::fromArray($body);
    }
}
