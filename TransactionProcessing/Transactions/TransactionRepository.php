<?php namespace Blog\TransactionProcessing\Transactions;

interface TransactionRepository
{
    public function byId(TransactionId $transactionId);
    public function store(Transaction $transaction);
}