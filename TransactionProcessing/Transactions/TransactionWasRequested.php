<?php namespace Blog\TransactionProcessing\Transactions;

final class TransactionWasRequested implements SerializablePayload
{
    public function __construct(
        private readonly TransactionId $transactionId,
        private readonly PaymentMethod $paymentMethod,
        private readonly TransactionAmount $amount,
        private readonly RequestedAt $requestedAt
    ) {
    }

    public function transactionId(): TransactionId
    {
        return $this->transactionId;
    }

    public function paymentMethod(): PaymentMethod
    {
        return $this->paymentMethod;
    }

    public function amount(): TransactionAmount
    {
        return $this->amount;
    }

    public function requestedAt(): RequestedAt
    {
        return $this->requestedAt;
    }

    public static function fromPayload(
        array $payload
    ): self {
        return new self(
            TransactionId::fromString($payload['transaction_id']),
            PaymentMethod::fromString($payload['payment_method']),
            TransactionAmount::fromCents($payload['amount_cents'], $payload['amount_currency']),
            RequestedAt::fromString($payload['requested_at_utc'])
        );
    }

    public function toPayload(): array
    {
        return [
            'id' => $this->transactionId->toString(),
            'payment_method' => $this->paymentMethod->toString(),
            'amount_cents' => $this->amount->cents(),
            'amount_currency' => $this->amount->currencyString(),
            'requested_at_utc' => $this->requestedAt->toMysqlDateTimeString()
        ];
    }
}