<?php

declare(strict_types=1);

namespace Samfelgar\Cartos\CashIn\Models\Webhook;

use Psr\Http\Message\RequestInterface;

readonly class CashInPayload
{
    public function __construct(
        public string $event,
        public string $token,
        public string $endToEndId,
        public string $txId,
        public string $transactionCode,
        public string $pixKey,
        public float $amount,
        public \DateTimeImmutable $createdAt,
        public ?string $information,
        public Payer $payer,
    ) {}

    public static function fromArray(array $data): CashInPayload
    {
        $createdAt = \DateTimeImmutable::createFromFormat(\DateTimeInterface::RFC3339_EXTENDED, $data['horario']);

        return new CashInPayload(
            $data['evento'],
            $data['token'],
            $data['endToEndId'],
            $data['txid'],
            $data['codigoTransacao'],
            $data['chavePix'],
            $data['valor'] / 100,
            $createdAt,
            $data['infoPagador'],
            new Payer(
                $data['pagador']['nome'],
                $data['pagador']['cpf_cnpj'],
                $data['pagador']['codigoBanco'],
            ),
        );
    }

    public static function fromRequest(RequestInterface $request): CashInPayload
    {
        $body = \json_decode((string)$request->getBody(), true);
        return self::fromArray($body);
    }
}
