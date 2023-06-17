<?php namespace Blog\TransactionProcessing\Transactions;

final class PaymentMethod
{
    const VALID_METHODS = [
        'ideal',
        'credit_card',
        'bancontact'
    ];
    
    private function __construct(
        private readonly string $method
    ) {
    }
    
    public static function fromString(
        string $method
    ): self {
        return new self($method);
    }

    public function toString(): string
    {
        return $this->method;
    }
}