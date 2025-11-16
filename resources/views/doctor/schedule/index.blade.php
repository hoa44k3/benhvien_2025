@extends('doctor.master')

@section('title', 'Quản lý Lịch khám của bác sĩ')

@section('body')
<div class="p-6 space-y-8">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-800">
            🩺 Quản lý Lịch khám & Ca làm việc
        </h1>
        <span class="text-sm text-gray-600">
            Ngày hôm nay: <strong>{{ \Carbon\Carbon::parse($today)->format('d/m/Y') }}</strong>
        </span>
    </div>

    {{-- 🔹 Ca làm việc --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        {{-- Lịch làm việc hôm nay --}}
        <div class="bg-white rounded-2xl shadow p-5 border border-gray-100">
            <h2 class="text-lg font-semibold text-green-700 mb-4">📅 Ca làm việc hôm nay</h2>

            @if($shifts->count() > 0)
                @foreach($shifts as $shift)
                    <div class="flex items-center justify-between mb-2">
                        <div class="px-3 py-1 rounded-lg text-sm font-medium
                            {{ $shift->shift === 'Sáng' ? 'bg-blue-100 text-blue-700' : ($shift->shift === 'Chiều' ? 'bg-yellow-100 text-yellow-700' : 'bg-gray-200 text-gray-600') }}">
                            {{ $shift->shift }}
                        </div>
                        @if($shift->room)
                            <span class="bg-green-100 text-green-700 px-3 py-1 rounded-lg text-sm">
                                {{ $shift->room }}
                            </span>
                        @endif
                    </div>
                @endforeach
            @else
                <p class="text-gray-500 italic">Chưa có ca làm việc hôm nay.</p>
            @endif
        </div>

        {{-- Cập nhật ca làm việc --}}
        <div class="bg-white rounded-2xl shadow p-5 border border-gray-100">
            <h2 class="text-lg font-semibold text-green-700 mb-4">🛠️ Cập nhật ca làm việc</h2>
            <form action="{{ route('doctor.schedule.updateShift') }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-sm font-medium text-gray-700">Ngày</label>
                    <input type="date" name="date" value="{{ $today }}" class="mt-1 w-full border-gray-300 rounded-lg">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Ca làm việc</label>
                    <select name="shift" class="mt-1 w-full border-gray-300 rounded-lg">
                        <option value="Sáng">Sáng</option>
                        <option value="Chiều">Chiều</option>
                        <option value="Nghỉ">Nghỉ</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Phòng / Ghi chú</label>
                    <input type="text" name="room" placeholder="VD: Phòng A01" class="mt-1 w-full border-gray-300 rounded-lg">
                </div>
                <button type="submit" class="w-full bg-green-600 text-white py-2 rounded-lg hover:bg-green-700">
                    💾 Lưu ca làm việc
                </button>
            </form>
        </div>
    </div>

    {{-- 🔹 Lịch khám --}}
    <div class="bg-white rounded-2xl shadow p-6 border border-gray-100">
        <div class="flex justify-between mb-4">
            <h2 class="text-lg font-semibold text-blue-700">📋 Lịch hẹn hôm nay</h2>
            <a href="{{ route('doctor.schedule.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                + Thêm lịch khám
            </a>
        </div>

        @if($appointments->count())
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($appointments as $a)
                    <div class="border rounded-xl p-4 bg-gray-50">
                        <div class="flex justify-between mb-1">
                            <span class="text-lg font-semibold text-gray-800">{{ $a->time }}</span>
                            <span class="px-3 py-1 text-xs rounded-full
                                {{ $a->status === 'Đang chờ' ? 'bg-yellow-100 text-yellow-700' : ($a->status === 'Đang khám' ? 'bg-blue-100 text-blue-700' : 'bg-green-100 text-green-700') }}">
                                {{ $a->status }}
                            </span>
                        </div>
                        <div class="text-gray-800 font-medium">{{ $a->patient_name }}</div>
                        <div class="text-gray-500 text-sm">{{ $a->notes ?? 'Không có ghi chú' }}</div>
                        <form action="{{ route('doctor.schedule.updateStatus', $a->id) }}" method="POST" class="mt-3">
                            @csrf
                            @method('PUT')
                            <select name="status" class="w-full border-gray-300 rounded-lg">
                                <option value="Đang chờ" {{ $a->status === 'Đang chờ' ? 'selected' : '' }}>Đang chờ</option>
                                <option value="Đang khám" {{ $a->status === 'Đang khám' ? 'selected' : '' }}>Đang khám</option>
                                <option value="Hoàn thành" {{ $a->status === 'Hoàn thành' ? 'selected' : '' }}>Hoàn thành</option>
                                <option value="Hủy hẹn" {{ $a->status === 'Hủy hẹn' ? 'selected' : '' }}>Hủy hẹn</option>
                            </select>
                            <button type="submit" class="w-full bg-blue-600 text-white mt-2 py-1.5 rounded-lg hover:bg-blue-700">
                                Cập nhật
                            </button>
                        </form>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-gray-500 italic text-center py-4">Không có lịch hẹn hôm nay.</p>
        @endif
    </div>
</div>
@endsection
