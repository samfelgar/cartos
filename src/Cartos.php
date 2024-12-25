<?php

declare(strict_types=1);

namespace Samfelgar\Cartos;

use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\MessageFormatter;
use GuzzleHttp\Middleware;
use Psr\Log\LoggerInterface;
use Samfelgar\Cartos\Auth\Authentication;
use Samfelgar\Cartos\CashIn\CashIn;
use Samfelgar\Cartos\CashOut\CashOut;

class Cartos
{
    private ?string $authorizationToken = null;
    private ?string $headerToken = null;

    public function __construct(
        private readonly Client $client,
    ) {}

    public static function instance(string $baseUrl, ?LoggerInterface $logger = null): Cartos
    {
        $handler = HandlerStack::create();
        if ($logger !== null) {
            $handler->push(Middleware::log($logger, new MessageFormatter(MessageFormatter::DEBUG)));
        }

        $client = new Client([
            'base_uri' => $baseUrl,
            'handler' => $handler,
        ]);

        return new Cartos($client);
    }

    public function setAuthorizationToken(string $authorizationToken): void
    {
        $this->authorizationToken = $authorizationToken;
    }

    public function setHeaderToken(string $headerToken): void
    {
        $this->headerToken = $headerToken;
    }

    public function authentication(): Authentication
    {
        return new Authentication($this->client);
    }

    public function cashIn(): CashIn
    {
        return new CashIn($this->client, $this->authorizationToken, $this->headerToken);
    }

    public function cashOut(): CashOut
    {
        return new CashOut($this->client, $this->authorizationToken, $this->headerToken);
    }
}
