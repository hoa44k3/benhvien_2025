@extends('admin.master')
@section('body')
<div class="container mt-4">
    <div class="row">
        <div class="col-md-5">
            <div class="card shadow mb-4">
                <div class="card-header bg-info text-white fw-bold">Nội dung tin nhắn</div>
                <div class="card-body">
                    <p><strong>Người gửi:</strong> {{ $contact->name }} ({{ $contact->email }})</p>
                    <p><strong>SĐT:</strong> {{ $contact->phone ?? '---' }}</p>
                    <p><strong>Ngày gửi:</strong> {{ $contact->created_at->format('d/m/Y H:i') }}</p>
                    <hr>
                    <h5 class="fw-bold text-primary">{{ $contact->subject }}</h5>
                    <p class="bg-light p-3 rounded">{{ $contact->message }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-7">
            <div class="card shadow">
                <div class="card-header bg-primary text-white fw-bold">Phản hồi</div>
                <div class="card-body">
                    @if($contact->status == 'replied')
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle me-1"></i> Đã trả lời lúc {{ \Carbon\Carbon::parse($contact->replied_at)->format('d/m/Y H:i') }}
                        </div>
                        <div class="mb-3">
                            <label class="fw-bold">Nội dung đã gửi:</label>
                            <div class="p-3 border rounded bg-light mt-1">{{ $contact->reply_message }}</div>
                        </div>
                        <a href="{{ route('contacts.index') }}" class="btn btn-secondary">Quay lại</a>
                    @else
                        <form action="{{ route('contacts.reply', $contact->id) }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label fw-bold">Nội dung trả lời (Gửi qua Email):</label>
                                <textarea name="reply_message" rows="6" class="form-control" placeholder="Nhập nội dung phản hồi..." required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary"><i class="fas fa-paper-plane me-1"></i> Gửi phản hồi</button>
                            <a href="{{ route('contacts.index') }}" class="btn btn-secondary">Hủy</a>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection