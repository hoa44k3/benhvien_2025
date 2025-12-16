<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PatientRescheduleMail extends Mailable
{
    use Queueable, SerializesModels;
    public $appointment;
    public $doctorName;
    /**
     * Create a new message instance.
     */
    public function __construct($appointment, $doctorName) {
        $this->appointment = $appointment;
        $this->doctorName = $doctorName;
    }
    public function build() {
        return $this->subject('Thông báo thay đổi lịch khám - SmartHospital')->view('emails.patient_reschedule');
    }
    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Patient Reschedule Mail',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'view.name',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
