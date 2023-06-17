<?php namespace Blog\TransactionProcessing\Transactions;

class RelationalTransactionRepository implements TransactionRepository
{
    public function __construct(
        private readonly SqlDatabase $db,
        private readonly EventDispatcher $events
    ) {
    }

    public function byId(
        TransactionId $transactionId
    ): Transaction {
        $transaction = $this->db->readFirst(
            <<<___
                select
                    id, payment_method, amount_cents, amount_currency, requested_at_utc
                from
                    transactions
                where
                    id = :id
            ___,
            [
                'id' => $transactionId->toString(),
            ]
        );

        if ( ! $transaction) {
            throw CanNotFindTransaction::byId($transactionId);
        }

        $transaction['captures'] = $this->db->readAll(
            <<<___
                select
                    id, amount_cents, amount_currency, captured_at_utc
                from
                    transaction_captures
                where
                    transaction_id = :transaction_id
            ___,
            [
                'transaction_id' => $transactionId->toString(),
            ]
        );

        return Transaction::deserialize($transaction);
    }

    public function store(
        Transaction $transaction
    ): void {
        $this->db->withinTransaction(
            function () use ($transaction) {
                $serialized = $transaction->serialize();

                $this->db->write(
                    <<<___
                        replace into transactions
                            (id, payment_method, amount_cents, amount_currency, requested_at_utc)
                        values
                            (:id, :payment_method, :amount_cents, :amount_currency, :requested_at_utc)
                    ___,
                    $serialized
                );

                $serialized['captures']->each(
                    fn(array $capture) => $this->db->write(
                        <<<___
                            replace into transaction_captures
                                (id, amount_cents, amount_currency, captured_at_utc)
                            values
                                (:id, :amount_cents, :amount_currency, :captured_at_utc)
                        ___,
                        $capture
                    )
                );

                $this->events->dispatch(
                    $transaction->flushEvents()
                );
            }
        );
    }
}
