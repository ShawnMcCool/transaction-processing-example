<?php namespace Blog\TransactionProcessing\Transactions;

final class TransactionId
{
    private function __construct(
        private readonly string $id
    ) {
    }

    public static function fromString(
        string $id
    ): self {
        return new self($id);
    }

    public function toString(): string
    {
        return $this->id;
    }
}