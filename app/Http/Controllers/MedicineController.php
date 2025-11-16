<?php

namespace App\Http\Controllers;

use App\Helpers\AuditHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Medicine;
use Carbon\Carbon;

class MedicineController extends Controller
{
    public function index()
    {
    //     $medicines = Medicine::orderBy('id', 'desc')->get();
    //      // Tính tổng số loại thuốc
    //     $totalMedicines = $medicines->count();

    //     // Tổng giá trị tồn kho (giá * tồn kho)
    //     // Tự động đổi đơn vị theo giá trị
    //     $totalStockValue = $medicines->sum(fn($m) => $m->price * $m->stock);

    //     if ($totalStockValue >= 1_000_000_000) {
    //         $formattedValue = number_format($totalStockValue / 1_000_000_000, 1) . ' tỷ VNĐ';
    //     } elseif ($totalStockValue >= 1_000_000) {
    //         $formattedValue = number_format($totalStockValue / 1_000_000, 1) . ' triệu VNĐ';
    //     } else {
    //         $formattedValue = number_format($totalStockValue) . ' VNĐ';
    //     }


    //     // Thuốc sắp hết hạn (hạn trong vòng 30 ngày) hoặc hết kho
    //     // chỉ những thuốc sắp hết hạn (≤ 30 ngày) hoặc hết hàng mới tính
    //    $soonExpired = $medicines->filter(function ($item) {
    //         if (!$item->expiry_date) return false;

    //         $expiry = Carbon::parse($item->expiry_date);
    //         $daysLeft = $expiry->isFuture() ? $expiry->diffInDays(now()) : 0;

    //         // sắp hết hạn nếu còn dưới 30 ngày hoặc hết hạn rồi
    //         $isExpiredSoon = $daysLeft <= 30;
    //         $isOutOfStock = $item->stock <= 0;

    //         return $isExpiredSoon || $isOutOfStock;
    //     })->count();
    //     return view('medicines.index', compact('medicines', 'totalMedicines', 'totalStockValue', 'soonExpired'));
    
     // Lấy toàn bộ thuốc (để hiển thị trong bảng)
    $medicines = Medicine::orderBy('id', 'desc')->get();

    // Tổng số loại thuốc
    $totalMedicines = $medicines->count();

    // Tổng giá trị tồn kho (tất cả thuốc)
    $totalStockValue = $medicines->sum(function ($m) {
        $price = $m->price ?? 0;
        $stock = $m->stock ?? 0;
        return $price * $stock;
    });

    // Định dạng linh hoạt: VNĐ / triệu / tỷ
    if ($totalStockValue >= 1_000_000_000) {
        $formattedTotalStock = number_format($totalStockValue / 1_000_000_000, 1) . ' tỷ VNĐ';
    } elseif ($totalStockValue >= 1_000_000) {
        $formattedTotalStock = number_format($totalStockValue / 1_000_000, 1) . ' triệu VNĐ';
    } else {
        $formattedTotalStock = number_format($totalStockValue) . ' VNĐ';
    }

    // Lọc thuốc có trạng thái "sắp hết"
    $lowStockMedicines = Medicine::where('status', 'sắp hết')->get();

    // Tính tổng giá trị của nhóm "sắp hết"
    $lowStockValue = $lowStockMedicines->sum(function ($m) {
        $price = $m->price ?? 0;
        $stock = $m->stock ?? 0;
        return $price * $stock;
    });

    // Format giá trị sắp hết kho
    if ($lowStockValue >= 1_000_000_000) {
        $formattedLowStockValue = number_format($lowStockValue / 1_000_000_000, 1) . ' tỷ VNĐ';
    } elseif ($lowStockValue >= 1_000_000) {
        $formattedLowStockValue = number_format($lowStockValue / 1_000_000, 1) . ' triệu VNĐ';
    } else {
        $formattedLowStockValue = number_format($lowStockValue) . ' VNĐ';
    }

    // Truyền dữ liệu sang view
    return view('medicines.index', compact(
        'medicines',
        'totalMedicines',
        'formattedTotalStock',
        'formattedLowStockValue'
    ));
}

    public function create()
    {
        return view('medicines.create');
    }

    public function store(Request $request)
    {
        try{
            $validated = $request->validate([
                'code' => 'required|unique:medicines,code',
                'name' => 'required',
                'category' => 'nullable|string',
                'stock' => 'required|integer|min:0',
                'min_stock' => 'nullable|integer|min:0',
                'unit' => 'required|string',
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
        return view('medicines.edit', compact('medicine'));
    }

    public function update(Request $request, Medicine $medicine)
    {
        try{
            $validated = $request->validate([
                'name' => 'required',
                'category' => 'nullable|string',
                'stock' => 'required|integer|min:0',
                'min_stock' => 'nullable|integer|min:0',
                'unit' => 'required|string',
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
