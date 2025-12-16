<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Appointment;
use App\Models\DoctorSite;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DoctorStatisticController extends Controller
{
    public function index()
    {
        $doctorId = Auth::id();
        $doctorSite = DoctorSite::where('user_id', $doctorId)->first();

        // 1. Thống kê tổng quan
        $totalPatients = Appointment::where('doctor_id', $doctorId)->distinct('user_id')->count('user_id');
        $totalAppointments = Appointment::where('doctor_id', $doctorId)->count();
        $completedAppointments = Appointment::where('doctor_id', $doctorId)->where('status', 'Hoàn thành')->count();

        // 2. Tính thu nhập tháng này (Logic giống ScheduleController)
        $currentMonth = Carbon::now()->month;
        $monthlyAppointments = Appointment::where('doctor_id', $doctorId)
            ->where('status', 'Hoàn thành')
            ->whereMonth('date', $currentMonth)
            ->count();
        
        $examFee = 200000; // Giả sử phí khám
        $commissionRate = $doctorSite->commission_exam_percent ?? 0;
        $estimatedIncome = ($monthlyAppointments * $examFee) * ($commissionRate / 100);

        // 3. Biểu đồ: Số lượng bệnh nhân 7 ngày gần nhất
        $chartData = Appointment::select(DB::raw('DATE(date) as date'), DB::raw('count(*) as total'))
            ->where('doctor_id', $doctorId)
            ->where('date', '>=', Carbon::now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $labels = $chartData->pluck('date');
        $data = $chartData->pluck('total');

        return view('doctor.statistics.index', compact(
            'totalPatients', 
            'totalAppointments', 
            'completedAppointments', 
            'estimatedIncome',
            'labels',
            'data'
        ));
    }
}
