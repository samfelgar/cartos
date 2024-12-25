<?php

declare(strict_types=1);

namespace Samfelgar\Cartos\CashOut\Models;

use Psr\Http\Message\ResponseInterface;

readonly class CashOutResponse
{
    public function __construct(
        public bool $success,
        public string $message,
        public string $transactionCode,
        public \DateTimeImmutable $createdAt,
    ) {}

    public static function fromArray(array $data): CashOutResponse
    {
        $createdAt = \DateTimeImmutable::createFromFormat(\DateTimeInterface::ATOM, $data['dataHoraTransacao']);

        return new CashOutResponse(
            (bool)$data['sucesso'],
            $data['mensagem'],
            $data['codigoTransacao'],
            $createdAt,
        );
    }

    public static function fromResponse(ResponseInterface $response): CashOutResponse
    {
        $body = \json_decode((string)$response->getBody(), true);
        return self::fromArray($body);
    }
}
