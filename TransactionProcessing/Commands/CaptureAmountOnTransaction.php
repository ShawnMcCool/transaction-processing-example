<?php namespace Blog\TransactionProcessing\Commands;

use Blog\TransactionProcessing\Captures\CaptureId;
use Blog\TransactionProcessing\Captures\CapturedAt;
use Blog\TransactionProcessing\Captures\CaptureAmount;
use Blog\TransactionProcessing\Transactions\TransactionId;
use Blog\TransactionProcessing\TransactionProcessingCommand;

final class CaptureAmountOnTransaction implements TransactionProcessingCommand
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
}