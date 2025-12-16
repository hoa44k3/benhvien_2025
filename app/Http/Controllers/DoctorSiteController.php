<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\DoctorSite;
use App\Models\User;
use App\Models\Department;
use App\Models\DoctorAttendance;
use App\Models\Prescription;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class DoctorSiteController extends Controller
{
    public function index()
    {
        // === PHẦN MỚI THÊM: TỰ ĐỘNG ĐỒNG BỘ ===
        // 1. Lấy role 'doctor'
        $doctorRole = Role::where('name', 'doctor')->first();
    if ($doctorRole) {
            // --- SỬA LỖI TẠI ĐÂY ---
            // Thay vì pluck('id') gây lỗi ambiguous
            // Hãy đổi thành pluck('users.id') để chỉ định rõ lấy ID từ bảng users
            $doctorUserIds = $doctorRole->users()->pluck('users.id'); 

            $existingSiteIds = DoctorSite::pluck('user_id');

            $missingIds = $doctorUserIds->diff($existingSiteIds);

            foreach ($missingIds as $id) {
                DoctorSite::create([
                    'user_id' => $id,
                    'department_id' => null,
                    'specialization' => 'Chưa cập nhật',
                    'bio' => null,
                    'rating' => 0,
                    'reviews_count' => 0,
                    'status' => 1, 
                    'base_salary' => 0,
                    'commission_exam_percent' => 0,
                    'commission_prescription_percent' => 0,
                    'commission_service_percent' => 0,
                    'experience_years' => 0,
                    'image' => null,
                    'bank_name' => null,
                    'bank_account_number' => null,
                    'bank_account_holder' => null,
                    'created_at' => now(),
                    'updated_at' => now(),

                    
                ]);
            }
        // === KẾT THÚC PHẦN ĐỒNG BỘ ===

        // Code cũ giữ nguyên: Lấy danh sách hiển thị ra view
        $doctors = DoctorSite::with('user', 'department')
            ->latest()
            ->paginate(10);

        return view('doctorsite.index', compact('doctors'));
    }
}

    public function create()
    {
        // Lấy danh sách user có role là doctor nhưng CHƯA có trong bảng doctor_sites
        $doctorRole = Role::where('name', 'doctor')->first();
        
        // Lấy tất cả user là doctor
        $allDoctorUsers = $doctorRole ? $doctorRole->users : collect();

        // Lọc ra những người chưa được tạo hồ sơ bác sĩ
        $existingDoctorIds = DoctorSite::pluck('user_id')->toArray();
        $users = $allDoctorUsers->whereNotIn('id', $existingDoctorIds);

        $departments = Department::all();

        return view('doctorsite.create', compact('users', 'departments'));
    }

    // Lưu bác sĩ mới
    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id' => 'required|exists:users,id|unique:doctor_sites,user_id',
            'department_id' => 'nullable|exists:departments,id',
            'specialization' => 'nullable|string|max:255',
            'bio' => 'nullable|string',
            'experience_years' => 'nullable|integer|min:0',
            'rating' => 'nullable|numeric|min:0|max:5',
            'review_count' => 'nullable|integer|min:0',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'status' => 'sometimes|boolean',
            
            // --- THÔNG TIN TÀI CHÍNH ---
            'base_salary' => 'nullable|numeric|min:0',
            'commission_exam_percent' => 'nullable|numeric|min:0|max:100',
            'commission_prescription_percent' => 'nullable|numeric|min:0|max:100',
            'commission_service_percent' => 'nullable|numeric|min:0|max:100',
            'bank_name' => 'nullable|string|max:255',
            'bank_account_number' => 'nullable|string|max:50',
            'bank_account_holder' => 'nullable|string|max:255',
        ]);

        $data['status'] = $request->has('status') ? (bool)$request->input('status') : 0;
        
        // Gán mặc định nếu không nhập
        $data['base_salary'] = $data['base_salary'] ?? 0;
        $data['commission_exam_percent'] = $data['commission_exam_percent'] ?? 0;
        $data['commission_prescription_percent'] = $data['commission_prescription_percent'] ?? 0;
        $data['commission_service_percent'] = $data['commission_service_percent'] ?? 0;

        DB::beginTransaction();
        try {
            if ($request->hasFile('image')) {
                $data['image'] = $request->file('image')->store('uploads/doctors', 'public');
            }

            DoctorSite::create($data);

            DB::commit();
            return redirect()->route('doctorsite.index')->with('success', 'Thêm hồ sơ bác sĩ thành công!');
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('[DoctorSite][store] error: '.$e->getMessage());
            return back()->withInput()->with('error', 'Lỗi khi thêm bác sĩ: '.$e->getMessage());
        }
    }

    public function edit(DoctorSite $doctor)
    {
        // Khi edit thì không cần chọn lại User, chỉ hiển thị tên
        $departments = Department::all();
        return view('doctorsite.edit', compact('doctor', 'departments'));
    }

    public function update(Request $request, DoctorSite $doctor)
    {
        $data = $request->validate([
            // DoctorSite Info
            'department_id' => 'nullable|exists:departments,id',
            'specialization' => 'nullable|string|max:255',
            'bio' => 'nullable|string',
            'experience_years' => 'nullable|integer|min:0',
            'rating' => 'nullable|numeric|min:0|max:5',
            'review_count' => 'nullable|integer|min:0',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'status' => 'sometimes|boolean',
            
            // --- THÔNG TIN TÀI CHÍNH ---
            'base_salary' => 'nullable|numeric|min:0',
            'commission_exam_percent' => 'nullable|numeric|min:0|max:100',
            'commission_prescription_percent' => 'nullable|numeric|min:0|max:100',
            'commission_service_percent' => 'nullable|numeric|min:0|max:100',
            'bank_name' => 'nullable|string|max:255',
            'bank_account_number' => 'nullable|string|max:50',
            'bank_account_holder' => 'nullable|string|max:255',

            // User Info (cập nhật tên/email nếu cần)
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$doctor->user_id,
        ]);

        DB::beginTransaction();
        try {
            // 1. Cập nhật bảng users
            $doctor->user->update([
                'name' => $data['name'],
                'email' => $data['email'],
            ]);

            // 2. Chuẩn bị dữ liệu cập nhật doctor_sites
            $doctorData = [
                'department_id' => $data['department_id'] ?? null,
                'specialization' => $data['specialization'] ?? null,
                'bio' => $data['bio'] ?? null,
                'experience_years' => $data['experience_years'] ?? 0,
                'rating' => $data['rating'] ?? 0,
                'review_count' => $data['review_count'] ?? 0,
                'status' => $request->has('status') ? (bool)$request->input('status') : 0,
                
                // Tài chính
                'base_salary' => $data['base_salary'] ?? 0,
                'commission_exam_percent' => $data['commission_exam_percent'] ?? 0,
                'commission_prescription_percent' => $data['commission_prescription_percent'] ?? 0,
                'commission_service_percent' => $data['commission_service_percent'] ?? 0,
                'bank_name' => $data['bank_name'] ?? null,
                'bank_account_number' => $data['bank_account_number'] ?? null,
                'bank_account_holder' => $data['bank_account_holder'] ?? null,
            ];

            if ($request->hasFile('image')) {
                if ($doctor->image) Storage::disk('public')->delete($doctor->image);
                $doctorData['image'] = $request->file('image')->store('uploads/doctors', 'public');
            }

            $doctor->update($doctorData);

            DB::commit();
            return redirect()->route('doctorsite.index')->with('success', 'Cập nhật thông tin bác sĩ thành công!');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Lỗi khi cập nhật: '.$e->getMessage());
        }
    }

    public function finance(Request $request, DoctorSite $doctor)
    {
    // 1. Lấy tháng/năm
    $month = $request->input('month', Carbon::now()->month);
    $year = $request->input('year', Carbon::now()->year);
    $daysInMonth = Carbon::createFromDate($year, $month)->daysInMonth; // Tổng số ngày trong tháng

    // 2. XỬ LÝ CHẤM CÔNG (ATTENDANCE)
    // Giả sử quy định chuẩn là 26 công/tháng
    $standardWorkDays = 26; 

    // Đếm số ngày bác sĩ có đi làm (status = present hoặc check_in có dữ liệu)
   $actualWorkDays = DoctorAttendance::where('doctor_id', $doctor->user_id)
    ->whereMonth('date', $month)
    ->whereYear('date', $year)
    ->whereIn('status', ['present', 'late'])
    ->count();

    // Tính lương cứng thực tế (Lương thỏa thuận / 26 * Số ngày làm)
    // Nếu làm đủ hoặc dư 26 công thì nhận full lương, nếu thiếu thì bị trừ
    $realBaseSalary = 0;
    if ($doctor->base_salary > 0) {
        $salaryPerDay = $doctor->base_salary / $standardWorkDays;
        $realBaseSalary = $salaryPerDay * $actualWorkDays;

        // Không được vượt quá lương cứng (nếu lỡ chấm công > 26 ngày)
        if ($realBaseSalary > $doctor->base_salary) {
            $realBaseSalary = $doctor->base_salary;
        }
    }

    // Tính số tiền bị trừ (để hiển thị cho rõ)
    $deductedSalary = $doctor->base_salary - $realBaseSalary;


    // 3. TÍNH HOA HỒNG KHÁM BỆNH & LẤY DANH SÁCH BỆNH NHÂN
    // Chỉ lấy ca đã HOÀN THÀNH (completed) -> Đây là tiền thật
    $completedAppointments = Appointment::where('doctor_id', $doctor->user_id)
        ->whereMonth('date', $month)
        ->whereYear('date', $year)
        ->where('status', 'completed') // 🔥 QUAN TRỌNG: Chỉ tính ca đã xong
        ->orderBy('date', 'asc')
        ->get();

    // Phí khám & Hoa hồng
    $examFee = $doctor->department ? $doctor->department->fee : 0; 
    $totalExamRevenue = $completedAppointments->count() * $examFee;
    $commissionExam = $totalExamRevenue * ($doctor->commission_exam_percent / 100);


    // 4. TÍNH HOA HỒNG ĐƠN THUỐC
    $prescriptions = Prescription::where('doctor_id', $doctor->user_id)
        ->whereMonth('created_at', $month)
        ->whereYear('created_at', $year)
        ->get();

    $totalDrugRevenue = $prescriptions->sum('total_amount'); 
    $commissionDrug = $totalDrugRevenue * ($doctor->commission_prescription_percent / 100);

    // 5. TỔNG THU NHẬP CUỐI CÙNG
    $totalIncome = $realBaseSalary + $commissionExam + $commissionDrug;

    return view('doctorsite.finance', compact(
        'doctor', 'month', 'year', 'totalIncome',
        'standardWorkDays', 'actualWorkDays', 'realBaseSalary', 'deductedSalary', // Biến cho chấm công
        'completedAppointments', 'examFee', 'totalExamRevenue', 'commissionExam',
        'prescriptions', 'totalDrugRevenue', 'commissionDrug'
    ));
    }
    public function destroy(DoctorSite $doctor)
    {
        if ($doctor->image) {
            Storage::disk('public')->delete($doctor->image);
        }
        $doctor->delete();
        return redirect()->route('doctorsite.index')->with('success', 'Đã xóa bác sĩ thành công!');
    }
    
     public function show(DoctorSite $doctor)
    {
        return view('doctorsite.show', compact('doctor'));
    }
}
            