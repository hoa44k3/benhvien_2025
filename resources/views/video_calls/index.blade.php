@extends('admin.master')
@section('body')
<div class="container-fluid px-4">
    <h1 class="mt-4">Lịch sử Cuộc gọi Video</h1>
    <div class="card mb-4">
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Bác sĩ</th>
                        <th>Bệnh nhân</th>
                        <th>Bắt đầu</th>
                        <th>Kết thúc</th>
                        <th>Thời lượng</th>
                        <th>Mã Lịch hẹn</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($calls as $call)
                    <tr>
                        <td>{{ $call->id }}</td>
                        <td>{{ $call->doctor->name }}</td>
                        <td>{{ $call->patient->name }}</td>
                        <td>{{ date('H:i d/m/Y', strtotime($call->start_time)) }}</td>
                        <td>
                            {{ $call->end_time ? date('H:i d/m/Y', strtotime($call->end_time)) : 'Chưa kết thúc' }}
                        </td>
                        <td>
                            <span class="badge bg-primary">{{ $call->duration ?? '---' }}</span>
                        </td>
                        <td>{{ $call->appointment->code }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $calls->links() }}
        </div>
    </div>
</div>
@endsection