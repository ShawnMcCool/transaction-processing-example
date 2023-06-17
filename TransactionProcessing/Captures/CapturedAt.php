<?php namespace Blog\TransactionProcessing\Captures;

use DateTimeZone;
use DateTimeImmutable;

final class CapturedAt
{
    private function __construct(
        private readonly DateTimeImmutable $capturedAt
    ) {}

    public static function fromString(
        string $capturedAtUtc
    ): self {
        return new self(
            new DateTimeImmutable($capturedAtUtc, new DateTimeZone('UTC'))
        );
    }

    public function toMysqlDateTimeString(): string
    {
        return $this->capturedAt->format('Y-m-d H:i:s');
    }
}