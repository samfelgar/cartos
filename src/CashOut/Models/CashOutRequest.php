<?php

declare(strict_types=1);

namespace Samfelgar\Cartos\CashOut\Models;

use Webmozart\Assert\Assert;

readonly class CashOutRequest implements \JsonSerializable
{
    public function __construct(
        public string $id,
        public float $amount,
        public PixInterface $destinationPix,
        public ?string $description = null,
    ) {
        Assert::greaterThan($this->amount, 0);
        Assert::lessThanEq($this->amount, 20_000);
        Assert::lengthBetween($this->id, 1, 36);
    }

    public function jsonSerialize(): array
    {
        $data = [
            'idEnvio' => $this->id,
            'valor' => $this->amount * 100,
            'chavePixDestino' => $this->destinationPix->value(),
        ];

        if ($this->description !== null) {
            $data['descricao'] = $this->description;
        }

        return $data;
    }
}
