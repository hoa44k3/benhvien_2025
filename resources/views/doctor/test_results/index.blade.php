@extends('doctor.master')

@section('title', 'Kết quả Xét nghiệm')

@section('body')
<div class="container mx-auto max-w-7xl px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800 border-l-4 border-indigo-500 pl-3">
            🧪 Kết quả Xét nghiệm
        </h2>
        <a href="{{ route('doctor.test_results.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 shadow transition">
            <i class="fas fa-upload mr-2"></i> Upload Kết quả
        </a>
    </div>

    {{-- Form Tìm kiếm --}}
    <form action="" method="GET" class="mb-6 flex gap-2 w-full md:w-1/3">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Tìm tên bệnh nhân..." class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500">
        <button type="submit" class="bg-gray-200 px-4 py-2 rounded-lg hover:bg-gray-300"><i class="fas fa-search"></i></button>
    </form>

    <div class="bg-white shadow-md rounded-lg overflow-hidden border border-gray-200">
        <table class="min-w-full leading-normal">
            <thead class="bg-gray-50 text-gray-600 uppercase text-xs font-bold">
                <tr>
                    <th class="px-5 py-3 text-left">Bệnh nhân</th>
                    <th class="px-5 py-3 text-left">Loại xét nghiệm</th>
                    <th class="px-5 py-3 text-left">Chẩn đoán/Kết luận</th>
                    <th class="px-5 py-3 text-center">File Kết quả</th>
                    <th class="px-5 py-3 text-center">Ngày tạo</th>
                </tr>
            </thead>
            <tbody>
                @forelse($results as $item)
                <tr class="border-b border-gray-200 hover:bg-gray-50">
                    <td class="px-5 py-4">
                        <p class="font-bold text-gray-900">{{ $item->user->name ?? 'N/A' }}</p>
                        <p class="text-xs text-gray-500">Mã: BN{{ $item->user_id }}</p>
                    </td>
                    <td class="px-5 py-4 font-semibold text-indigo-700">
                        {{ $item->test_name }}
                    </td>
                    <td class="px-5 py-4 text-gray-600 italic">
                        {{ Str::limit($item->diagnosis, 50) }}
                    </td>
                    <td class="px-5 py-4 text-center">
                        @if($item->file_path)
                            <a href="{{ asset('storage/' . $item->file_path) }}" target="_blank" class="text-blue-600 hover:underline">
                                <i class="fas fa-file-alt mr-1"></i> Xem file
                            </a>
                        @else
                            <span class="text-gray-400">Không có file</span>
                        @endif
                    </td>
                    <td class="px-5 py-4 text-center text-gray-500 text-sm">
                        {{ $item->created_at->format('d/m/Y H:i') }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-5 py-8 text-center text-gray-500">Chưa có kết quả xét nghiệm nào.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-5 py-3 border-t">
            {{ $results->links() }}
        </div>
    </div>
</div>
@endsection