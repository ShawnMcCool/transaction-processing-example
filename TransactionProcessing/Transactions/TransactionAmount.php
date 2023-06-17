<?php namespace Blog\TransactionProcessing\Transactions;

use Blog\TransactionProcessing\Money;
use Blog\TransactionProcessing\Currency;

final class TransactionAmount
{
    private function __construct(
        private readonly Money $amount
    ) {}

    public static function fromCents(
        int $cents,
        string $currency
    ): self {
        return new self(
            new Money($cents, new Currency($currency))
        );
    }

    public function cents(): int
    {
        return $this->amount->toCents();
    }

    public function currencyString(): string
    {
        return $this->amount->currency()->toString();
    }
}