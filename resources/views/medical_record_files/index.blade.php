@extends('admin.master')

@section('body')
<div class="p-6">

    <h1 class="text-2xl font-bold mb-4">Danh sách file hồ sơ bệnh án</h1>

    <a href="{{ route('medical_record_files.create') }}" 
       class="bg-blue-600 text-white px-4 py-2 rounded mb-4 inline-block">
        + Thêm file
    </a>

    @if(session('success'))
        <div class="bg-green-200 text-green-800 p-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <table class="w-full border text-sm">
        <thead>
            <tr class="bg-gray-200 font-semibold">
                <th>ID</th>
                <th>Hồ sơ</th>
                <th>File</th>
                <th>Người upload</th>
                <th>Trạng thái</th>
                <th>Ngày</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>

        @foreach($files as $file)
            <tr class="border">
                <td>{{ $file->id }}</td>
                <td>{{ $file->medical_record_id }}</td>

                <td>
                    <a href="{{ asset('storage/'.$file->file_path) }}" target="_blank" class="text-blue-600 underline">
                        {{ $file->original_name }}
                    </a>
                </td>

                <td>{{ $file->uploader->name ?? 'N/A' }}</td>

                <td>
                    <span class="px-2 py-1 rounded 
                        {{ $file->status == 'active' ? 'bg-green-200 text-green-700' : 'bg-gray-300 text-gray-700' }}">
                        {{ $file->status }}
                    </span>
                </td>

                <td>{{ $file->created_at->format('d/m/Y') }}</td>

                <td class="space-x-2">
                    <a href="{{ route('medical_record_files.show',$file->id) }}" class="text-blue-600">Xem</a>
                    <a href="{{ route('medical_record_files.edit',$file->id) }}" class="text-yellow-600">Sửa</a>
                    <button onclick="deleteFile({{ $file->id }})" class="text-red-600">Xóa</button>
                </td>
            </tr>
        @endforeach

        </tbody>
    </table>

    <div class="mt-4">
        {{ $files->links() }}
    </div>
</div>

<script>
function deleteFile(id) {
    if (!confirm("Xóa file này?")) return;

    fetch('/medical_record_files/' + id, {
        method: 'DELETE',
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) location.reload();
    });
}
</script>
@endsection
