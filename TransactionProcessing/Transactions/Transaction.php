<?php namespace Blog\TransactionProcessing\Transactions;

use Monolith\Collections\Collection;
use Blog\TransactionProcessing\Captures\Capture;
use Blog\TransactionProcessing\Captures\CaptureId;
use Blog\TransactionProcessing\Captures\CapturedAt;
use Blog\TransactionProcessing\Captures\CaptureAmount;
use Blog\TransactionProcessing\Captures\AmountWasCaptured;
use EventSourcery\EventSourcery\EventSourcing\DomainEvents;

final class Transaction
{
    private function __construct(
        private readonly TransactionId $id,
        private readonly PaymentMethod $paymentMethod,
        private readonly TransactionAmount $amount,
        private readonly RequestedAt $requestedAt,
        private Collection $captures
    ) {
    }

    public static function request(
        TransactionId $id,
        PaymentMethod $paymentMethod,
        TransactionAmount $amount,
        RequestedAt $requestedAt
    ): self {
        $transaction = new self(
            $id,
            $paymentMethod,
            $amount,
            $requestedAt,
            Collection::empty()
        );

        $transaction->pendingEvents[] = new TransactionWasRequested(
            $id,
            $paymentMethod,
            $amount,
            $requestedAt
        );

        return $transaction;
    }

    public function capture(
        CaptureId $captureId,
        CaptureAmount $captureAmount,
        CapturedAt $capturedAt
    ): void {
        if ($this->totalRemainingAmount()->isLessThan($captureAmount)) {
            throw CanNotCaptureAmount::remainingTransactionAmountIsInsufficient(
                $this->id,
                $captureId,
                $this->totalRemainingAmount(),
                $captureAmount
            );
        }

        $this->captures = $this->captures->add(
            new Capture(
                $captureId,
                $captureAmount,
                $capturedAt
            )
        );
        
        $this->pendingEvents[] = new AmountWasCaptured(
            $this->id,
            $captureId,
            $captureAmount,
            $capturedAt
        );
    }

    private array $pendingEvents = [];

    public static function deserialize(
        array $payload
    ): self {
        return new self(
            TransactionId::fromString($payload['id']),
            PaymentMethod::fromString($payload['payment_method']),
            TransactionAmount::fromCents($payload['amount_cents'], $payload['amount_currency']),
            RequestedAt::fromString($payload['requested_at_utc']),
            $payload['captures']->map(
                fn(array $capturePayload) => Capture::deserialize($capturePayload)
            )
        );
    }

    public function serialize(): array
    {
        return [
            'id' => $this->id->toString(),
            'payment_method' => $this->paymentMethod->toString(),
            'amount_cents' => $this->amount->cents(),
            'amount_currency' => $this->amount->currencyString(),
            'requested_at_utc' => $this->requestedAt->toMysqlDateTimeString(),
            'captures' => $this->captures->map(
                fn(Capture $capture) => $capture->serialize()
            ),
        ];
    }

    public function flushEvents(): DomainEvents
    {
        $events = $this->pendingEvents;
        $this->pendingEvents = [];
        return DomainEvents::fromArray($events);
    }
}