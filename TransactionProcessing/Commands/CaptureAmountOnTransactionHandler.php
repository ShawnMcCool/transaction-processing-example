<?php namespace Blog\TransactionProcessing\Commands;

use Blog\TransactionProcessing\Transactions\TransactionRepository;

final class CaptureAmountOnTransactionHandler
{
    private function __construct(
        private readonly TransactionRepository $transactions
    ) {
    }

    public function handle(
        CaptureAmountOnTransaction $capture
    ): self {
        $transaction = $this->transactions->byId(
            $capture->transactionId()
        );
        
        $transaction->capture(
            $capture->captureId(),
            $capture->captureAmount(),
            $capture->capturedAt()
        );
        
        $this->transactions->store($transaction);
    }
}