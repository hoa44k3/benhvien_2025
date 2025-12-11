@extends('admin.master')

@section('body')
<div class="p-6">

    <h1 class="text-2xl font-bold mb-4">Danh sách liên hệ</h1>

    @if(session('success'))
        <div class="bg-green-200 text-green-900 p-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <table class="w-full border text-sm">
        <thead class="bg-gray-100">
            <tr>
                <th class="border p-2">ID</th>
                <th class="border p-2">Tên</th>
                <th class="border p-2">Email</th>
                <th class="border p-2">Chủ đề</th>
                <th class="border p-2">Ngày gửi</th>
                <th class="border p-2">Hành động</th>
            </tr>
        </thead>

        <tbody>
            @forelse($contacts as $item)
            <tr>
                <td class="border p-2">{{ $item->id }}</td>
                <td class="border p-2">{{ $item->name }}</td>
                <td class="border p-2">{{ $item->email }}</td>
                <td class="border p-2">{{ $item->subject }}</td>
                <td class="border p-2">{{ $item->created_at->format('d/m/Y') }}</td>

                <td class="border p-2">
                    <form action="{{ route('contact.destroy', $item->id) }}" 
                          method="POST"
                          onsubmit="return confirm('Bạn chắc muốn xóa?')">
                        @csrf
                        @method('DELETE')
                        <button class="px-3 py-1 bg-red-600 text-white rounded">
                            Xóa
                        </button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center p-4">Không có dữ liệu</td>
            </tr>
            @endforelse
        </tbody>

    </table>

    <div class="mt-4">
        {{ $contacts->links() }}
    </div>
</div>
@endsection
