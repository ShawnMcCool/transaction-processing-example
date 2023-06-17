<?php namespace Blog\TransactionProcessing\Transactions;

use DateTimeZone;
use DateTimeImmutable;

final class RequestedAt
{
    private function __construct(
        private readonly DateTimeImmutable $requestedAt
    ) {}

    public static function fromString(
        string $requestedAtUtc
    ): self {
        return new self(
            new DateTimeImmutable($requestedAtUtc, new DateTimeZone('UTC'))
        );
    }

    public function toMysqlDateTimeString(): string
    {
        return $this->requestedAt->format('Y-m-d H:i:s');
    }
}