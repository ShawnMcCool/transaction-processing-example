<?php namespace Blog\TransactionProcessing\Captures;

final class Capture
{
    public function __construct(
        private readonly CaptureId $id,
        private readonly CaptureAmount $amount,
        private readonly CapturedAt $capturedAt
    ) {
    }

    public static function deserialize(
        array $payload
    ): self {
        return new self(
            CaptureId::fromString($payload['id']),
            CaptureAmount::fromCents($payload['amount_cents'], $payload['amount_currency']),
            CapturedAt::fromString($payload['captured_at_utc'])
        );
    }

    public function serialize(): array
    {
        return [
            'id' => $this->id->toString(),
            'amount_cents' => $this->amount->cents(),
            'amount_currency' => $this->amount->currencyString(),
            'captured_at_utc' => $this->capturedAt->toMysqlDateTimeString(),
        ];
    }
}