<?php

namespace App\Mail;

use App\Models\MedicalRecord;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class MedicalRecordCompletedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;
public $record;
    /**
     * Create a new message instance.
     */
    // hoàn tất hồ sơ khám
   public function __construct(MedicalRecord $record)
    {
        $this->record = $record;
    }
public function build()
    {
        return $this->subject('Kết quả khám bệnh & Đơn thuốc - ' . $this->record->user->name)
                    ->view('emails.medical_record_completed');
    }
    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Medical Record Completed Mail',
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
