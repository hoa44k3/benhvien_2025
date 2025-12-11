@extends('admin.master')

@section('body')
<div class="p-6">

    <h1 class="text-2xl font-bold mb-4">Danh sách kết quả xét nghiệm</h1>

    <a href="{{ route('test_results.create') }}"
       class="bg-blue-600 text-white px-4 py-2 rounded mb-4 inline-block">
        + Thêm xét nghiệm
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
                <th>Bệnh nhân</th>
                <th>Tên xét nghiệm</th>
                <th>Phòng xét nghiệm</th>
                <th>Ngày</th>
                <th>Trạng thái</th>
                <th>File</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>

        @foreach($results as $item)
            <tr class="border">
                <td>{{ $item->id }}</td>
                
                <td>{{ $item->patient->name ?? 'N/A' }}</td>

                <td>{{ $item->test_name }}</td>

                <td>{{ $item->lab_name }}</td>

                <td>{{ \Carbon\Carbon::parse($item->date)->format('d/m/Y') }}</td>

                <td>
                    <span class="px-2 py-1 rounded 
                        {{ $item->status == 'completed' ? 'bg-green-200 text-green-700' : 'bg-yellow-200 text-yellow-700' }}">
                        {{ $item->status }}
                    </span>
                </td>

                <td>
                    @if($item->file_main)
                        <a href="{{ asset('storage/'.$item->file_main) }}" 
                           class="text-blue-600 underline" target="_blank">
                            Xem file
                        </a>
                    @else
                        <span class="text-gray-500">Không có</span>
                    @endif
                </td>

                <td class="space-x-2">
                    <a href="{{ route('test_results.show', $item->id) }}" class="text-blue-600">Xem</a>
                    <a href="{{ route('test_results.edit', $item->id) }}" class="text-yellow-600">Sửa</a>
                    <button onclick="deleteItem({{ $item->id }})" class="text-red-600">Xóa</button>
                </td>
            </tr>
        @endforeach

        </tbody>
    </table>

    <div class="mt-4">
        {{ $results->links() }}
    </div>
</div>


<script>
function deleteItem(id) {
    if (!confirm("Xóa kết quả xét nghiệm này?")) return;

    fetch('/test_results/' + id, {
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
