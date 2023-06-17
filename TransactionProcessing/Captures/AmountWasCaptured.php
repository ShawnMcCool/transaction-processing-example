<?php namespace Blog\TransactionProcessing\Captures;

use Blog\TransactionProcessing\Transactions\TransactionId;

final class AmountWasCaptured
{
    public function __construct(
        private readonly TransactionId $transactionId,
        private readonly CaptureId $captureId,
        private readonly CaptureAmount $amount,
        private readonly CapturedAt $capturedAt
    ) {
    }

    public function transactionId(): TransactionId
    {
        return $this->transactionId;
    }

    public function captureId(): CaptureId
    {
        return $this->captureId;
    }

    public function amount(): CaptureAmount
    {
        return $this->amount;
    }

    public function capturedAt(): CapturedAt
    {
        return $this->capturedAt;
    }

    public static function fromPayload(
        array $payload
    ): self {
        return new self(
            TransactionId::fromString($payload['transaction_id']),
            CaptureId::fromString($payload['capture_id']),
            CaptureAmount::fromCents($payload['amount_cents'], $payload['amount_currency']),
            CapturedAt::fromString($payload['captured_at_utc'])
        );
    }

    public function toPayload(): array
    {
        return [
            'transaction_id' => $this->transactionId->toString(),
            'capture_id' => $this->captureId->toString(),
            'amount_cents' => $this->amount->cents(),
            'amount_currency' => $this->amount->currencyString(),
            'captured_at_utc' => $this->capturedAt->toMysqlDateTimeString(),
        ];
    }
}