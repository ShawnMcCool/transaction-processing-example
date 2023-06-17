<?php namespace Blog\TransactionProcessing\Commands;

use Blog\TransactionProcessing\Transactions\RequestedAt;
use Blog\TransactionProcessing\Transactions\TransactionId;
use Blog\TransactionProcessing\Transactions\PaymentMethod;
use Blog\TransactionProcessing\TransactionProcessingCommand;
use Blog\TransactionProcessing\Transactions\TransactionAmount;

final class RequestTransaction implements TransactionProcessingCommand
{
    private function __construct(
        private readonly TransactionId $id,
        private readonly PaymentMethod $paymentMethod,
        private readonly TransactionAmount $amount,
        private readonly RequestedAt $requestedAt,
    ) {
    }

    public function id(): TransactionId
    {
        return $this->id;
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
}