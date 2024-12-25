<?php

declare(strict_types=1);

namespace Samfelgar\Cartos\CashOut\Models\Webhook;

use Psr\Http\Message\RequestInterface;

readonly class CashOutPayload
{
    public function __construct(
        public Event $event,
        public string $token,
        public string $id,
        public ?string $endToEndId,
        public string $transactionCode,
        public Status $status,
        public ?string $pixKey,
        public float $amount,
        public \DateTimeImmutable $createdAt,
        public ?Recipient $recipient,
        public ?Error $error,
    ) {}

    public static function fromArray(array $data): CashOutPayload
    {
        $createdAt = \DateTimeImmutable::createFromFormat(\DateTimeInterface::RFC3339_EXTENDED, $data['horario']);

        $error = null;
        if (isset($data['erro'])) {
            $error = new Error(
                $data['erro']['origem'] ?? null,
                $data['erro']['motivo'] ?? null,
                $data['erro']['mensagem'] ?? null,
            );
        }

        $recipient = null;
        if (isset($data['recebedor'])) {
            $recipient = new Recipient(
                $data['recebedor']['nome'],
                $data['recebedor']['codigoBanco'],
                $data['recebedor']['cpf_cnpj'] ?? $data['recebedor']['cpf'] ?? $data['recebedor']['cnpj'],
            );
        }

        return new CashOutPayload(
            Event::from($data['evento']),
            $data['token'],
            $data['idEnvio'],
            $data['endToEndId'],
            $data['codigoTransacao'],
            Status::from($data['status']),
            $data['chavePix'],
            $data['valor'],
            $createdAt,
            $recipient,
            $error,
        );
    }

    public static function fromRequest(RequestInterface $request): CashOutPayload
    {
        $body = \json_decode((string)$request->getBody(), true);
        return self::fromArray($body);
    }
}
