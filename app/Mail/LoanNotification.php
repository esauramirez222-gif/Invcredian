<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Loan;

class LoanNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $loan;
    public $type;

    public function __construct(Loan $loan, $type)
    {
        $this->loan = $loan;
        $this->type = $type;
    }

    public function envelope(): Envelope
    {
        // El asunto del correo cambia según el tipo de notificación
        $subjects = [
            'new_request' => 'NUEVA SOLICITUD: ' . $this->loan->applicant_name . ' ' . $this->loan->applicant_last_name,
            'approved'    => '¡Tu préstamo ha sido APROBADO! - Credian',
            'rejected'    => 'Actualización de tu solicitud de préstamo',
            'returned'    => 'Confirmación de Devolución - Credian',
        ];

        return new Envelope(subject: $subjects[$this->type] ?? 'Notificación de Préstamo');
    }

    public function content(): Content
    {
        return new Content(view: 'emails.loan_notification');
    }
}