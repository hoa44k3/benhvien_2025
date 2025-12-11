@extends('admin.master')

@section('body')
<div class="p-6 max-w-3xl mx-auto">

    <div class="bg-white rounded-xl shadow p-6 border">
        <h2 class="text-2xl font-bold mb-4">Chi tiết kết quả xét nghiệm</h2>

        <p><strong>Bệnh nhân:</strong> {{ $testResult->patient->name }}</p>
        <p><strong>Ngày xét nghiệm:</strong> {{ $testResult->date }}</p>
        <p><strong>Loại xét nghiệm:</strong> {{ $testResult->test_type }}</p>
        <p><strong>Kết quả:</strong> {{ $testResult->result }}</p>
        <p><strong>Bác sĩ:</strong> {{ $testResult->doctor->name ?? '—' }}</p>
        <p><strong>Khoa:</strong> {{ $testResult->department->name ?? '—' }}</p>

        <p>
            <strong>Trạng thái:</strong>
            <span class="px-2 py-1 rounded text-white
            @if($testResult->status=='pending') bg-yellow-500
            @elseif($testResult->status=='completed') bg-green-600
            @else bg-red-600 @endif">
                {{ $testResult->status }}
            </span>
        </p>

        <p class="mt-3"><strong>Ghi chú:</strong> {{ $testResult->notes }}</p>

        @if($testResult->file_path)
        <div class="mt-4">
            <strong>File xét nghiệm:</strong>  
            <a target="_blank" href="{{ asset('storage/'.$testResult->file_path) }}"
               class="text-blue-600">Xem file</a>
        </div>
        @endif

        <div class="mt-6">
            <a href="{{ route('test_results.index') }}"
               class="px-4 py-2 bg-gray-600 text-white rounded">Quay lại</a>
        </div>
    </div>
</div>
@endsection
