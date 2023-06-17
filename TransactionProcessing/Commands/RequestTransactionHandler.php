<?php namespace Blog\TransactionProcessing\Commands;

use Blog\TransactionProcessing\Transactions\Transaction;
use Blog\TransactionProcessing\Transactions\TransactionRepository;

final class RequestTransactionHandler
{
    private function __construct(
        private readonly TransactionRepository $transactions
    ) {
    }
    
    public function handle(
        RequestTransaction $request
    ): void {
        $transaction = Transaction::request(
            $request->id(),
            $request->paymentMethod(),
            $request->amount(),
            $request->requestedAt()
        );
        
        $this->transactions->store($transaction);
    }
}