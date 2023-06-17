<?php namespace Blog\TransactionProcessing\Transactions;

use DomainException;
use Blog\TransactionProcessing\TransactionProcessingException;

final class CanNotFindTransaction extends DomainException implements TransactionProcessingException
{
    public static function byId(
        TransactionId $transactionId
    ): self {
        return new self(
            "Can not find transaction with id '{$transactionId->$transactionId->toString()}'."
        );
    }
}