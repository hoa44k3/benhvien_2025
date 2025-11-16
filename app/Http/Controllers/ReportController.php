<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Medicine;
use App\Models\Appointment;
use App\Models\Revenue;
use Carbon\Carbon;
//use Barryvdh\DomPDF\Facade\Pdf;
use Barryvdh\DomPDF\Facade as PDF; 
class ReportController extends Controller
{
    public function index()
    {
        return view('reports.index');
    }

    
    /**
     * Xử lý khi người dùng chọn xem báo cáo
     */
    public function viewReport(Request $request)
    {
        $type = $request->report_type;
        $time = $request->time_type;

        switch ($type) {
            case 'revenue':
                $data = $this->getRevenueReport($time);
                break;

            case 'doctor_performance':
                $data = $this->getDoctorPerformance($time);
                break;

            case 'medicine_stock':
                $data = $this->getMedicineStockReport();
                break;

            default:
                $data = collect();
        }

        return response()->json($data);
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

    /**
     * Báo cáo tình trạng kho thuốc
     */
    private function getMedicineStockReport()
    {
        return Medicine::select('name', 'stock', 'min_stock', 'expiry_date', 'status')
            ->orderBy('name')
            ->get();
    }

    public function exportPDF()
{
    // Lấy dữ liệu thuốc
    $data = \App\Models\Medicine::all();

    // Khởi tạo DomPDF qua service container (không cần Facade)
    $pdf = app('dompdf.wrapper');

    // Load view vào PDF
    $pdf->loadView('reports.pdf', compact('data'));

    // Trả về file PDF để tải về
    return $pdf->download('bao-cao-thuoc.pdf');
}

}
