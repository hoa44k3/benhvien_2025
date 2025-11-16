<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Staff;
use App\Models\Appointment;
use App\Models\Revenue;
use App\Models\HospitalRoom;
use App\Models\Medicine;
use App\Models\SystemAlert;
use App\Models\AuditLog;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
       public function index()
    {
        // Tổng quan
        $totalPatients = User::count();
        $todayAppointments = Appointment::whereDate('created_at', Carbon::today())->count();
        $activeStaff = Staff::where('status', 'active')->count();

        // 👉 Tính doanh thu từ bảng medicines
        $monthlyRevenue = Medicine::whereMonth('created_at', Carbon::now()->month)
            ->sum(DB::raw('price * stock'));

        // Phòng bệnh
        $totalRooms = HospitalRoom::count();
        $availableRooms = HospitalRoom::where('status', 'Trống')->count();
        $usedRooms = HospitalRoom::where('status', 'Đang sử dụng')->count();
        $maintenanceRooms = HospitalRoom::where('status', 'Bảo trì')->count();

        // Thuốc
        $lowMedicines = Medicine::whereColumn('stock', '<', 'min_stock')->count();
        $expiredMedicines = Medicine::where('expiry_date', '<', Carbon::today())->count();

        // Hoạt động gần đây
        $recentActivities = AuditLog::latest()->take(5)->get();

        return view('dashboard.index', compact(
            'totalPatients',
            'todayAppointments',
            'monthlyRevenue',
            'activeStaff',
            'totalRooms',
            'availableRooms',
            'usedRooms',
            'maintenanceRooms',
            'lowMedicines',
            'expiredMedicines',
            'recentActivities'
        ));
    }
}
