<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\MedicalRecord;
use App\Models\Prescription;
use App\Models\TestResult;
use App\Models\User;
use App\Models\Department;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class MedicalRecordController extends Controller
{
    /**
     * Display a listing of the medical records.
     */
    // public function index()
    // {
    //     $user = Auth::user();

    //     if (!$user) {
    //         return redirect()->route('login')->with('error', 'Bạn cần đăng nhập để xem hồ sơ bệnh án.');
    //     }

    //     $medicalRecords = MedicalRecord::where('user_id', $user->id)
    //         ->with(['doctor', 'department']) 
    //         ->orderBy('date', 'desc')
    //         ->get();

    //     return view('medical_records.index', compact('medicalRecords', 'user'));
    // }
    public function index()
{
    $user = Auth::user();

    if (!$user) {
        return redirect()->route('login')->with('error', 'Bạn cần đăng nhập để xem hồ sơ bệnh án.');
    }

    $medicalRecords = MedicalRecord::where('user_id', $user->id)
        ->with(['doctor', 'department']) 
        ->orderBy('date', 'desc')
        ->paginate(10); // <--- SỬA: Đổi get() thành paginate(10)

    return view('medical_records.index', compact('medicalRecords', 'user'));
}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::all(); 
        $doctors = User::whereHas('roles', fn($q) => $q->where('name', 'doctor'))->get();
        $departments = Department::all();

        return view('medical_records.create', compact('users', 'doctors', 'departments'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'title' => 'required|string|max:255',
            'date' => 'required|date',
            'doctor_id' => 'nullable|exists:users,id',
            'department_id' => 'nullable|exists:departments,id',
            'appointment_id' => 'nullable|exists:appointments,id',
            'diagnosis' => 'nullable|string',
            'diagnosis_primary' => 'nullable|string|max:255',
            'diagnosis_secondary' => 'nullable|string|max:255',
            'treatment' => 'nullable|string',
            'symptoms' => 'nullable|string',
            'vital_signs' => 'nullable|array', 
            'status' => 'required|in:chờ_khám,đang_khám,đã_khám,hủy',
            'next_checkup' => 'nullable|date',
        ]);

        $data = $request->all();

        if ($request->has('vital_signs')) {
            $data['vital_signs'] = json_encode($request->vital_signs);
        }

        MedicalRecord::create($data);

        return redirect()->route('medical_records.index')->with('success', 'Thêm hồ sơ bệnh án thành công.');
    }

    /**
     * Display the specified resource.
     */
    public function show(MedicalRecord $medical_record)
    {
        $medical_record->load([
            'user', 
            'doctor', 
            'department', 
            'prescriptions.items', 
            'testResults', 
            'files'
        ]); 

        return view('medical_records.show', compact('medical_record'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MedicalRecord $medical_record)
    {
        $users = User::all(); 
        $doctors = User::whereHas('roles', fn($q) => $q->where('name', 'doctor'))->get();
        $departments = Department::all();

        return view('medical_records.edit', [
            'medical_record' => $medical_record,
            'users' => $users,
            'doctors' => $doctors,
            'departments' => $departments
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MedicalRecord $medical_record)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'title' => 'required|string|max:255',
            'date' => 'required|date',
            'doctor_id' => 'nullable|exists:users,id',
            'department_id' => 'nullable|exists:departments,id',
            'appointment_id' => 'nullable|exists:appointments,id',
            'diagnosis' => 'nullable|string',
            'diagnosis_primary' => 'nullable|string|max:255',
            'diagnosis_secondary' => 'nullable|string|max:255',
            'treatment' => 'nullable|string',
            'symptoms' => 'nullable|string',
            'vital_signs' => 'nullable|array',
            'status' => 'required|in:chờ_khám,đang_khám,đã_khám,hủy',
            'next_checkup' => 'nullable|date',
        ]);

        $data = $request->all();

        if ($request->has('vital_signs')) {
            $data['vital_signs'] = json_encode($request->vital_signs);
        }

        $medical_record->update($data);

        return redirect()->route('medical_records.index')->with('success', 'Cập nhật hồ sơ bệnh án thành công.');
    }
public function startExam(MedicalRecord $medical_record)
{
    if ($medical_record->status == 'chờ_khám') {
        $medical_record->update(['status' => 'đang_khám']);
        return back()->with('success', 'Đã bắt đầu ca khám. Vui lòng nhập thông tin chẩn đoán.');
    }
    return back()->with('error', 'Trạng thái không hợp lệ để bắt đầu.');
}

public function cancel(MedicalRecord $medical_record)
{
    if ($medical_record->status != 'đã_khám') {
        $medical_record->update(['status' => 'hủy']);
        return back()->with('success', 'Đã hủy hồ sơ khám bệnh này.');
    }
    return back()->with('error', 'Không thể hủy hồ sơ đã hoàn tất.');
}
    /**
     * Hoàn tất khám bệnh và Tự động tạo hóa đơn
     */
//     public function complete(MedicalRecord $medical_record)
//     {
//         try {
//             DB::beginTransaction();

//             // 1. Cập nhật trạng thái hồ sơ
//             $medical_record->update(['status' => 'đã_khám']);

//             // 2. Tính toán chi phí
//             // Phí khám (Lấy từ Khoa hoặc mặc định)
//             $examFee = $medical_record->department->fee ?? 150000;

//             // Phí thuốc (Lấy từ đơn thuốc mới nhất)
//             $prescription = $medical_record->prescriptions()->latest()->first();
//             $medicineTotal = 0;
//             if ($prescription) {
//                 $medicineTotal = $prescription->items->sum(function($item) {
//                     return ($item->price ?? 0) * ($item->quantity ?? 1);
//                 });
//             }

//             $totalAmount = $examFee + $medicineTotal;

//             // 3. Tạo Hóa đơn (Invoice Header)
//             $invoice = Invoice::create([
//                 'code' => 'HD-' . strtoupper(Str::random(8)),
//                 'user_id' => $medical_record->user_id,
//                 'medical_record_id' => $medical_record->id,
//                 'appointment_id' => $medical_record->appointment_id,
//                 'prescription_id' => $prescription ? $prescription->id : null, // Cột mới thêm
//                 'total' => $totalAmount,
//                 'status' => 'unpaid',
//                 'created_by' => Auth::id(),
//                 'issued_date' => now(),
//             ]);

//             // 4. Tạo Chi tiết Hóa đơn (Invoice Items)
            
//             // Dòng 1: Tiền công khám
//             DB::table('invoice_items')->insert([
//                 'invoice_id' => $invoice->id,
//                 'item_type' => 'service',
//                 'item_name' => 'Phí khám chuyên khoa ' . ($medical_record->department->name ?? 'Tổng quát'),
//                 'quantity' => 1,
//                 'price' => $examFee,
//                 'total' => $examFee,
//                 'created_at' => now(),
//                 'updated_at' => now(),
//             ]);

//             // Dòng 2...n: Tiền thuốc (nếu có đơn thuốc)
//             if ($prescription) {
//                 foreach ($prescription->items as $item) {
//                     DB::table('invoice_items')->insert([
//                         'invoice_id' => $invoice->id,
//                         'item_type' => 'medicine',
//                         'item_id' => $item->medicine_id, // Nếu có link tới bảng medicines
//                         'item_name' => $item->medicine_name . ' (' . $item->quantity . ' ' . ($item->unit ?? '') . ')',
//                         'quantity' => $item->quantity,
//                         'price' => $item->price,
//                         'total' => ($item->price * $item->quantity),
//                         'created_at' => now(),
//                         'updated_at' => now(),
//                     ]);
//                 }
//             }

//             DB::commit();
// return redirect()->route('medical_records.index')
//                      ->with('success', 'Ca khám đã hoàn tất.');
//         } catch (\Exception $e) {
//             DB::rollBack();
//             return back()->with('error', 'Lỗi khi tạo hóa đơn: ' . $e->getMessage());
//         }
//     }
public function complete(MedicalRecord $medical_record)
{
    if ($medical_record->status != 'đang_khám') {
        return back()->with('error', 'Hồ sơ này không ở trạng thái đang khám.');
    }

    try {
        DB::beginTransaction();

        // 1. Cập nhật trạng thái về Đã khám
        $medical_record->update(['status' => 'đã_khám']);

        // 2. Tính toán tiền
        $examFee = $medical_record->department->fee ?? 150000;
        
        $prescription = $medical_record->prescriptions()->latest()->first();
        $medicineTotal = 0;
        
        // Kiểm tra kỹ nếu có đơn thuốc thì mới tính
        if ($prescription) {
            // Đảm bảo quan hệ items được load để tính toán
            $prescription->load('items'); 
            $medicineTotal = $prescription->items->sum(function($item) {
                return ($item->price ?? 0) * ($item->quantity ?? 1);
            });
            $prescription->update(['status' => 'Đã phát thuốc']);
        }

        $totalAmount = $examFee + $medicineTotal;

        // 3. Tạo Hóa đơn
        $invoice = Invoice::create([
            'code' => 'HD-' . strtoupper(Str::random(8)),
            'user_id' => $medical_record->user_id,
            'medical_record_id' => $medical_record->id,
            'appointment_id' => $medical_record->appointment_id,
            'prescription_id' => $prescription ? $prescription->id : null,
            'total' => $totalAmount,
            'status' => 'unpaid',
            'created_by' => Auth::id(),
            'issued_date' => now(),
        ]);

        // 4. Lưu chi tiết hóa đơn (Invoice Items)
        // Item 1: Phí khám
        DB::table('invoice_items')->insert([
            'invoice_id' => $invoice->id,
            'item_type' => 'service', // Đảm bảo cột này khớp với migration
            'item_name' => 'Phí khám chuyên khoa ' . ($medical_record->department->name ?? 'Tổng quát'),
            'quantity' => 1,
            'price' => $examFee,
            'total' => $examFee,
            'created_at' => now(), 'updated_at' => now(),
        ]);

        // Item 2: Thuốc (nếu có)
        if ($prescription) {
            foreach ($prescription->items as $item) {
                DB::table('invoice_items')->insert([
                    'invoice_id' => $invoice->id,
                    'item_type' => 'medicine',
                    'item_id' => $item->medicine_id,
                    'item_name' => $item->medicine_name . ' (' . $item->quantity . ')',
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                    'total' => ($item->price * $item->quantity),
                    'created_at' => now(), 'updated_at' => now(),
                ]);
            }
        }

        DB::commit(); // <--- CHỐT DỮ LIỆU TẠI ĐÂY

        // 5. Chuyển hướng về trang Danh sách (Index) như bạn muốn
        return redirect()->route('medical_records.index')
                         ->with('success', 'Hoàn tất khám bệnh thành công! Trạng thái đã chuyển sang Đã khám.');

    } catch (\Exception $e) {
        DB::rollBack(); // <--- NẾU LỖI, QUAY LẠI TỪ ĐẦU (Trạng thái sẽ không đổi)
        
        // Quan trọng: Hiển thị lỗi chi tiết để biết đường sửa
        return back()->with('error', 'Lỗi hệ thống: ' . $e->getMessage());
    }
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MedicalRecord $medical_record)
    {
        try {
            $medical_record->delete();
            return back()->with('success', 'Xóa hồ sơ thành công!');
        } catch (\Exception $e) {
            return back()->with('error', 'Lỗi khi xóa hồ sơ: ' . $e->getMessage());
        }
    }

    public function download($id)
    {
        $record = MedicalRecord::with(['user', 'doctor', 'testResults', 'prescriptions'])->findOrFail($id);
        return response()->json([
            'message' => 'Tính năng tải PDF đang được phát triển',
            'record' => $record,
        ]);
    }
}