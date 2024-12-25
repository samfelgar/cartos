<?php

declare(strict_types=1);

namespace Samfelgar\Cartos\CashIn\Models;

use Webmozart\Assert\Assert;

readonly class CashInRequest implements \JsonSerializable
{
    public function __construct(
        public ?string $txId,
        public ?string $additionalInformation,
        public float $amount,
        public int $expiresIn,
    ) {
        Assert::nullOrLengthBetween($this->txId, 26, 36);
        Assert::greaterThan($this->amount, 0);
        Assert::greaterThan($this->expiresIn, 0);
    }

    public function jsonSerialize(): array
    {
        $data = [
            'valor' => $this->amount * 100,
            'tempoExpiracao' => $this->expiresIn,
        ];

        if ($this->txId !== null) {
            $data['txId'] = $this->txId;
        }

        if ($this->additionalInformation !== null) {
            $data['informacaoAdicional'] = $this->additionalInformation;
        }

        return $data;
    }
}
