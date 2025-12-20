<?php

namespace App\Http\Controllers;

use App\Helpers\AuditHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MedicineCategory; // Import model mới
use App\Models\MedicineUnit;
use App\Models\Medicine;
use Carbon\Carbon;

class MedicineController extends Controller
{
    public function index(Request $request)
    {
        // 1. Khởi tạo Query Builder
      $query = Medicine::with(['medicineCategory', 'medicineUnit']);

        // 2. Xử lý Tìm kiếm (Keyword: Mã hoặc Tên)
        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'like', '%' . $keyword . '%')
                  ->orWhere('code', 'like', '%' . $keyword . '%');
            });
        }

       // 3. Lọc theo Phân loại (Dựa trên ID danh mục)
        // 3. Lọc theo Phân loại (Sửa tên cột input cho khớp View)
        if ($request->filled('medicine_category_id')) {
            $query->where('medicine_category_id', $request->medicine_category_id);
        }
        // 4. Xử lý Lọc theo Cảnh báo (Tồn kho thấp / Hết hạn)
        if ($request->filled('alert')) {
            if ($request->alert == 'low_stock') {
                // Lọc thuốc có tồn kho <= tồn tối thiểu (hoặc mặc định là 10)
                $query->whereRaw('stock <= COALESCE(min_stock, 10)');
            } elseif ($request->alert == 'expired') {
                // Lọc thuốc đã hết hạn hoặc sắp hết hạn (trong 60 ngày)
                $query->where(function($q) {
                    $q->whereDate('expiry_date', '<', now()) // Đã hết hạn
                      ->orWhereDate('expiry_date', '<=', now()->addDays(60)); // Sắp hết hạn
                });
            }
        }

        // Lấy dữ liệu thuốc đã lọc và phân trang (10 item/trang)
        $medicines = $query->orderBy('id', 'desc')->paginate(10)->withQueryString();
        
        // --- PHẦN TÍNH TOÁN DASHBOARD (Thống kê trên TOÀN BỘ dữ liệu, không phụ thuộc bộ lọc) ---
        $allMedicines = Medicine::all(); // Lấy tất cả để tính toán thống kê chung
        // 1. Tổng số loại thuốc
        $totalMedicines = $allMedicines->count();
        // 2. Tổng giá trị tồn kho
        $totalStockValue = $allMedicines->sum(fn($m) => ($m->price ?? 0) * ($m->stock ?? 0));
        $formattedTotalStock = $this->formatCurrency($totalStockValue);

        // 3. Giá trị thuốc sắp hết kho (Stock <= Min Stock)
        $lowStockValue = $allMedicines->filter(function ($m) {
            return $m->stock <= ($m->min_stock ?? 10);
        })->sum(fn($m) => ($m->price ?? 0) * ($m->stock ?? 0));
        $formattedLowStockValue = $this->formatCurrency($lowStockValue);

        // 4. Số lượng thuốc hết hạn
        $expiredCount = $allMedicines->where('expiry_date', '<', now())->count();

        // Lấy danh sách Categories để đổ vào Select Box lọc
        // $categories = Medicine::select('category')->distinct()->whereNotNull('category')->pluck('category');
        // Lấy danh sách Category để đổ vào Select Box lọc ở View Index
        $categories = MedicineCategory::all();
        return view('medicines.index', compact(
            'medicines',
            'categories',
            'totalMedicines',
            'formattedTotalStock',
            'formattedLowStockValue',
            'expiredCount'
        ));
    }
    // Hàm phụ trợ để format tiền tệ (Tỷ/Triệu/VNĐ)
    private function formatCurrency($value)
    {
        if ($value >= 1_000_000_000) {
            return number_format($value / 1_000_000_000, 1) . ' tỷ VNĐ';
        } elseif ($value >= 1_000_000) {
            return number_format($value / 1_000_000, 1) . ' triệu VNĐ';
        }
        return number_format($value) . ' VNĐ';
    }

   public function create()
    {
        // Lấy danh sách để đổ vào dropdown
        $categories = MedicineCategory::all();
        $units = MedicineUnit::all();
        
        return view('medicines.create', compact('categories', 'units'));
    }

    public function store(Request $request)
    {
        try{
            $validated = $request->validate([
                'code' => 'required|unique:medicines,code',
                'name' => 'required',
              'medicine_category_id' => 'nullable|exists:medicine_categories,id', 
                'medicine_unit_id'     => 'nullable|exists:medicine_units,id',
                'stock' => 'required|integer|min:0',
                'min_stock' => 'nullable|integer|min:0',
                'price' => 'required|numeric|min:0',
                'expiry_date' => 'nullable|date',
                'status' => 'required|string',
                'supplier' => 'nullable|string',
        ]);

        Medicine::create($validated);
         // 🔹 Ghi log thành công
            AuditHelper::log('Tạo tài khoản mới', $request->name, 'Thành công');
        return redirect()->route('medicines.index')->with('success', 'Thêm thuốc thành công!');
        }catch(\Exception $e){
                // 🔹 Ghi log thất bại
            AuditHelper::log('Tạo tài khoản mới', $request->name ?? 'Không rõ', 'Thất bại');
            return redirect()->back()->with('error', 'Lỗi khi thêm thuốc: ' . $e->getMessage());
        }
       
    }

   public function edit(Medicine $medicine)
    {
        $categories = MedicineCategory::all();
        $units = MedicineUnit::all();
        return view('medicines.edit', compact('medicine', 'categories', 'units'));
    }
    public function update(Request $request, Medicine $medicine)
    {
        try{
            $validated = $request->validate([
                'name' => 'required',
              'medicine_category_id' => 'nullable|exists:medicine_categories,id',
                'medicine_unit_id'     => 'nullable|exists:medicine_units,id',
                'stock' => 'required|integer|min:0',
                'min_stock' => 'nullable|integer|min:0',
                
                'price' => 'required|numeric|min:0',
                'expiry_date' => 'nullable|date',
                'status' => 'required|string',
                'supplier' => 'nullable|string',
        ]);

        $medicine->update($validated);
            // 🔹 Ghi log thành công
                AuditHelper::log('Cập nhật thông tin nhân viên', $medicine->name, 'Thành công');
        return redirect()->route('medicines.index')->with('success', 'Cập nhật thuốc thành công!');
        }catch(\Exception $e){
                // 🔹 Ghi log thất bại
                AuditHelper::log('Cập nhật thông tin nhân viên', $medicine->name ?? 'Không rõ', 'Thất bại');
            return redirect()->back()->with('error', 'Lỗi khi cập nhật thuốc: ' . $e->getMessage());
        }
        
    }

    public function destroy(Medicine $medicine)
    {
        $medicine->delete();
        return redirect()->route('medicines.index')->with('success', 'Xóa thuốc thành công!');
    }
}
