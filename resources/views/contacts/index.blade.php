@extends('admin.master')
@section('body')
<div class="container-fluid mt-4">
    <h3><i class="fas fa-envelope-open-text me-2"></i> Hộp thư Liên hệ</h3>
    
    <div class="card shadow mt-3">
        <div class="card-body p-0">
            <table class="table table-hover mb-0 align-middle">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-3">Người gửi</th>
                        <th>Chủ đề</th>
                        <th>Ngày gửi</th>
                        <th>Trạng thái</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($contacts as $contact)
                    <tr class="{{ $contact->status == 'pending' ? 'fw-bold bg-white' : 'bg-light text-muted' }}">
                        <td class="ps-3">
                            {{ $contact->name }}<br>
                            <small class="text-muted">{{ $contact->email }}</small>
                        </td>
                        <td>{{ Str::limit($contact->subject, 30) }}</td>
                        <td>{{ $contact->created_at->format('d/m/Y H:i') }}</td>
                        <td>
                            @if($contact->status == 'pending')
                                <span class="badge bg-warning text-dark">Chờ phản hồi</span>
                            @else
                                <span class="badge bg-success">Đã trả lời</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('contacts.show', $contact->id) }}" class="btn btn-sm btn-primary">Xem & Trả lời</a>
                            <form action="{{ route('contacts.destroy', $contact->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Xóa tin nhắn này?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="card-footer">{{ $contacts->links() }}</div>
    </div>
</div>
@endsection