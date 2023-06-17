<?php namespace Blog\TransactionProcessing\Captures;

final class CaptureId
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