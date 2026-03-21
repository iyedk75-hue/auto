<?php

namespace App\Notifications;

use App\Models\PaymentRecord;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PaymentValidatedNotification extends Notification
{
    use Queueable;

    public function __construct(
        private readonly PaymentRecord $payment,
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'kind' => 'payment_validated',
            'payment_id' => $this->payment->id,
            'amount' => (string) $this->payment->amount,
            'payment_method' => $this->payment->payment_method,
            'transfer_reference' => $this->payment->transfer_reference,
            'title' => 'Votre paiement a ete valide',
            'body' => sprintf(
                'Votre paiement de %s a ete confirme. L\'acces aux cours et aux quiz est maintenant active.',
                number_format((float) $this->payment->amount, 2, ',', ' ')
            ),
        ];
    }
}