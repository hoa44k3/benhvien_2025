<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Staff;
use App\Models\Appointment;
use App\Models\Medicine;
use App\Models\AuditLog;
use App\Models\DoctorSite;
use App\Models\Invoice;
use App\Models\Prescription; // Thêm Model Đơn thuốc
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
       // 1. TỔNG BỆNH NHÂN (Lọc chính xác type = patient)
        $totalPatients = User::where('type', 'patient')->count();

        // 2. LỊCH HẸN HÔM NAY
        $todayAppointments = Appointment::whereDate('date', Carbon::today())->count();

        // 3. TỔNG BÁC SĨ (Thay cho Nhân viên)
        // Đếm số lượng bác sĩ đã có hồ sơ DoctorSite (hoặc lọc User type=doctor)
        $totalDoctors = DoctorSite::count();

        // 4. DOANH THU THÁNG NAY (Thay cho Giá trị kho thuốc - Quan trọng hơn)
        $monthlyRevenue = Invoice::where('status', 'paid')
            ->whereMonth('paid_at', Carbon::now()->month)
            ->whereYear('paid_at', Carbon::now()->year)
            ->sum('total');

        // 5. THỐNG KÊ ĐƠN THUỐC
        $todayPrescriptions = Prescription::whereDate('created_at', Carbon::today())->count();
        
        $pendingPrescriptions = Prescription::where('status', 'Đang kê')
                                            ->orWhere('status', 'Đang chờ')
                                            ->count();
                                            
        $completedPrescriptions = Prescription::where('status', 'Đã phát thuốc')->count();

        // 6. CẢNH BÁO THUỐC (Vẫn giữ để quản lý kho)
        $lowMedicines = Medicine::whereColumn('stock', '<=', DB::raw('COALESCE(min_stock, 10)'))->count();
        $expiredMedicines = Medicine::where('expiry_date', '<', Carbon::today())->count();

        // 7. HOẠT ĐỘNG GẦN ĐÂY
        $recentActivities = AuditLog::with('user')->latest()->take(5)->get();

        return view('dashboard.index', compact(
            'totalPatients',
            'todayAppointments',
            'totalDoctors',        // Biến mới
            'monthlyRevenue',      // Biến mới
            'todayPrescriptions',
            'pendingPrescriptions',
            'completedPrescriptions',
            'lowMedicines',
            'expiredMedicines',
            'recentActivities'
        ));
    }
}