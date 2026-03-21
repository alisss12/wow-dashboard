<?php

namespace App\Observers;

use App\Models\PaymentRequest;

class PaymentRequestObserver
{
    /**
     * Handle the PaymentRequest "created" event.
     */
    public function created(PaymentRequest $paymentRequest): void
    {
        //
    }

    public function updated(PaymentRequest $paymentRequest): void
    {
        // Only trigger if an Admin just approved this request
        if ($paymentRequest->isDirty('status') && $paymentRequest->status === 'approved') {
            
            $user = $paymentRequest->user;
            $amount = $paymentRequest->amount;

            if ($paymentRequest->type === 'deposit') {
                $user->increment('balance', $amount);
                
                \App\Models\LedgerTransaction::create([
                    'user_id' => $user->id,
                    'amount' => $amount,
                    'type' => 'deposit',
                    'description' => "Account Deposit via {$paymentRequest->gateway}",
                    'reference_id' => $paymentRequest->id,
                    'reference_type' => PaymentRequest::class,
                ]);
            }

            if ($paymentRequest->type === 'withdrawal') {
                // Ensure they have enough balance
                if ($user->balance >= $amount) {
                    $user->decrement('balance', $amount);
                    
                    \App\Models\LedgerTransaction::create([
                        'user_id' => $user->id,
                        // Ledger shows withdrawals as negative amounts for clarity
                        'amount' => -$amount,
                        'type' => 'withdrawal',
                        'description' => "Cash Out via {$paymentRequest->gateway}" . ($paymentRequest->payout_character ? " to {$paymentRequest->payout_character} ({$paymentRequest->payout_realm})" : ""),
                        'reference_id' => $paymentRequest->id,
                        'reference_type' => PaymentRequest::class,
                    ]);
                } else {
                    // Revert the approval if they don't have enough funds
                    $paymentRequest->updateQuietly([
                        'status' => 'declined',
                        'admin_notes' => 'Declined automatically: Insufficient Ledger Balance.'
                    ]);
                }
            }
        }
    }

    /**
     * Handle the PaymentRequest "deleted" event.
     */
    public function deleted(PaymentRequest $paymentRequest): void
    {
        //
    }

    /**
     * Handle the PaymentRequest "restored" event.
     */
    public function restored(PaymentRequest $paymentRequest): void
    {
        //
    }

    /**
     * Handle the PaymentRequest "force deleted" event.
     */
    public function forceDeleted(PaymentRequest $paymentRequest): void
    {
        //
    }
}
