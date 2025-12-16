<p>Chào {{ $contact->name }},</p>
<p>Cảm ơn bạn đã liên hệ. Đây là phản hồi của chúng tôi về vấn đề: <strong>{{ $contact->subject }}</strong></p>
<div style="background: #f9f9f9; padding: 15px; border-left: 4px solid green;">
    {!! nl2br(e($contact->reply_message)) !!}
</div>
<p>Trân trọng,<br>Đội ngũ hỗ trợ.</p>