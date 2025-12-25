<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\Medicine;
use App\Models\Appointment;
use App\Models\Revenue;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
//use Barryvdh\DomPDF\Facade\Pdf;
use Barryvdh\DomPDF\Facade as PDF; 
class ReportController extends Controller
{
    public function index()
    {// 1. Lấy tham số lọc (Mặc định tháng hiện tại)
        $month = $request->month ?? Carbon::now()->month;
        $year = $request->year ?? Carbon::now()->year;

        // --- KHỐI 1: THỐNG KÊ TỔNG QUAN ---
        
        // Tổng doanh thu (Đã thanh toán)
        $monthlyRevenue = Invoice::where('status', 'paid')
            ->whereMonth('paid_at', $month)
            ->whereYear('paid_at', $year)
            ->sum('total');

        // Tổng số ca khám hoàn thành
        // Lưu ý: Đảm bảo trạng thái 'Hoàn thành' khớp với Database của bạn
        $completedExams = Appointment::where('status', 'Hoàn thành') 
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->count();

        // Bệnh nhân mới
        $newPatients = User::where('type', 'patient')
            ->whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->count();

        // --- KHỐI 2: BIỂU ĐỒ DOANH THU 12 THÁNG ---
        $annualRevenueData = [];
        for ($i = 1; $i <= 12; $i++) {
            $revenue = Invoice::where('status', 'paid')
                ->whereYear('paid_at', $year)
                ->whereMonth('paid_at', $i)
                ->sum('total');
            $annualRevenueData[] = $revenue;
        }

        // --- KHỐI 3: DOANH THU THEO KHOA ---
        $revenueByDept = DB::table('invoices')
            ->join('appointments', 'invoices.appointment_id', '=', 'appointments.id')
            ->join('users as doctors', 'appointments.doctor_id', '=', 'doctors.id')
            ->join('doctor_sites', 'doctors.id', '=', 'doctor_sites.user_id')
            ->join('departments', 'doctor_sites.department_id', '=', 'departments.id')
            ->where('invoices.status', 'paid')
            ->whereMonth('invoices.paid_at', $month)
            ->whereYear('invoices.paid_at', $year)
            ->select('departments.name', DB::raw('SUM(invoices.total) as total'))
            ->groupBy('departments.name')
            ->pluck('total', 'departments.name');

        $deptLabels = $revenueByDept->keys();
        $deptValues = $revenueByDept->values();

        // --- KHỐI 4: TOP BÁC SĨ (SỬA LỖI TẠI ĐÂY) ---
        // Sử dụng Eloquent Model để trả về Object, giúp View gọi được $item->doctor
      // Chúng ta Join trực tiếp các bảng để lấy dữ liệu, không qua Model
        $topDoctors = DB::table('appointments')
            ->join('users', 'appointments.doctor_id', '=', 'users.id')
            ->leftJoin('doctor_sites', 'users.id', '=', 'doctor_sites.user_id')
            ->leftJoin('departments', 'doctor_sites.department_id', '=', 'departments.id')
            ->where('appointments.status', 'Hoàn thành') // Đảm bảo đúng trạng thái trong DB
            ->whereMonth('appointments.date', $month)
            ->whereYear('appointments.date', $year)
            ->select(
                'users.name as doctor_name',            // Tên bác sĩ
                'departments.name as department_name',  // Tên khoa
                'departments.fee as exam_fee',          // Phí khám
                DB::raw('count(appointments.id) as total_exams') // Số ca khám
            )
            ->groupBy('users.id', 'users.name', 'departments.name', 'departments.fee') // Group by đủ các cột select
            ->orderByDesc('total_exams')
            ->limit(5)
            ->get(); 

        return view('reports.index', compact(
            'month', 'year',
            'monthlyRevenue', 'completedExams', 'newPatients',
            'annualRevenueData',
            'deptLabels', 'deptValues',
            'topDoctors'
        ));
    }

    
    /**
     * Xử lý khi người dùng chọn xem báo cáo
     */
    public function viewReport(Request $request)
    {
        $type = $request->report_type;
        $time = $request->time_type; // month, quarter, year

        switch ($type) {
            case 'service_revenue':
                $data = $this->getServiceRevenueReport($time);
                break;

            case 'doctor_kpi':
                $data = $this->getDoctorKPI($time);
                break;

            case 'medicine_stock':
                $data = $this->getMedicineStockReport();
                break;
            
            // Tạm thời bỏ Lab KPI vì bảng Invoice hiện tại chưa có cột tách riêng tiền Xét nghiệm rõ ràng
            // Trừ khi xét nghiệm được tính chung vào Appointment
            case 'lab_kpi': 
                 $data = collect(); 
                 break;

            default:
                $data = collect();
        }

        return response()->json($data);
    }
    /**
     * 1. Báo cáo Doanh thu theo loại (Khám vs Thuốc)
     * Logic: Dựa vào khóa ngoại để phân loại
     */
    private function getServiceRevenueReport($time)
    {
        $query = Invoice::where('status', 'paid'); // Chỉ lấy hóa đơn đã thanh toán

        // Lọc thời gian
        $this->applyTimeFilter($query, $time, 'paid_at');

        // Sử dụng CASE WHEN để đặt tên loại dịch vụ dựa trên cột ID có dữ liệu
        return $query->selectRaw("
            CASE 
                WHEN prescription_id IS NOT NULL THEN 'Bán thuốc'
                WHEN appointment_id IS NOT NULL THEN 'Khám bệnh'
                ELSE 'Dịch vụ khác' 
            END as label, 
            SUM(total) as total
        ")
        ->groupBy('label')
        ->get();
    }

    /**
     * 2. KPI Bác sĩ: Số ca khám & Doanh thu từ tiền khám
     * Logic: Join từ Invoice -> Appointment -> Doctor
     */
    private function getDoctorKPI($time)
    {
        // Chúng ta cần lấy doanh thu từ bảng invoices, nhưng nhóm theo bác sĩ trong bảng appointments
        $query = DB::table('invoices')
            ->join('appointments', 'invoices.appointment_id', '=', 'appointments.id')
            ->join('users as doctors', 'appointments.doctor_id', '=', 'doctors.id') // Giả sử bác sĩ nằm trong bảng users
            ->where('invoices.status', 'paid')
            ->selectRaw('
                doctors.name as label, 
                COUNT(invoices.id) as total_appointments, 
                SUM(invoices.total) as total_revenue
            ');

        // Lọc thời gian dựa trên ngày thanh toán hóa đơn
        $this->applyTimeFilter($query, $time, 'invoices.paid_at');

        return $query->groupBy('doctors.id', 'doctors.name')->get();
    }
    /**
     * 3. Cảnh báo kho thuốc (Giữ nguyên)
     */
    private function getMedicineStockReport()
    {
        return Medicine::select('name as label', 'stock as total')
            ->orderBy('stock', 'asc')
            ->take(10)
            ->get();
    }

    /**
     * Helper lọc thời gian
     */
    private function applyTimeFilter($query, $time, $column)
    {
        $now = Carbon::now();
        if ($time === 'month') {
            $query->whereMonth($column, $now->month)->whereYear($column, $now->year);
        } elseif ($time === 'quarter') {
            $query->whereRaw('QUARTER('.$column.') = ?', [$now->quarter])->whereYear($column, $now->year);
        } else {
            $query->whereYear($column, $now->year);
        }
    }

    /**
     * Báo cáo doanh thu theo tháng / quý / năm
     */
    private function getRevenueReport($time)
    {
        $query = Revenue::query()->whereYear('created_at', Carbon::now()->year);

        if ($time === 'month') {
            $query->selectRaw('MONTH(created_at) as label, SUM(amount) as total')
                  ->groupBy('label')->orderBy('label');
        } elseif ($time === 'quarter') {
            $query->selectRaw('QUARTER(created_at) as label, SUM(amount) as total')
                  ->groupBy('label')->orderBy('label');
        } else { // year
            $query->selectRaw('YEAR(created_at) as label, SUM(amount) as total')
                  ->groupBy('label')->orderBy('label');
        }

        return $query->get();
    }

    /**
     * Báo cáo hiệu suất bác sĩ
     */
    private function getDoctorPerformance($time)
    {
        return Appointment::selectRaw('doctor_id, COUNT(*) as total_appointments, 
            SUM(CASE WHEN status = "Hoàn thành" THEN 1 ELSE 0 END) as completed')
            ->groupBy('doctor_id')
            ->with('doctor:id,name')
            ->get();
    }


  public function exportPDF() {
    $data = \App\Models\Medicine::orderBy('name')->get(); // Lấy danh sách sắp xếp theo tên
    $pdf = app('dompdf.wrapper');
    $pdf->loadView('reports.pdf', compact('data'));
    return $pdf->download('bao-cao-ton-kho-'.date('d-m-Y').'.pdf');
}

}
