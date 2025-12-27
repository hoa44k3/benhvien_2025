<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\DoctorSite;
use App\Models\User;
use App\Models\Department;
use App\Models\DoctorAttendance;
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
        // === TỰ ĐỘNG ĐỒNG BỘ USER -> DOCTOR_SITE ===
        $doctorRole = Role::where('name', 'doctor')->first();
        if ($doctorRole) {
            $doctorUserIds = $doctorRole->users()->pluck('users.id'); 
            $existingSiteIds = DoctorSite::pluck('user_id');
            $missingIds = $doctorUserIds->diff($existingSiteIds);

            foreach ($missingIds as $id) {
                DoctorSite::create([
                    'user_id' => $id,
                    'status' => 1, 
                    'base_salary' => 0,
                    'commission_exam_percent' => 0,
                ]);
            }
        }
        // ===========================================

        $doctors = DoctorSite::with('user', 'department')
            ->latest()
            ->paginate(10);

        return view('doctorsite.index', compact('doctors'));
    }

    public function create()
    {
        $doctorRole = Role::where('name', 'doctor')->first();
        $allDoctorUsers = $doctorRole ? $doctorRole->users : collect();
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
            // --- THÔNG TIN UY TÍN (MỚI) ---
            'degree' => 'nullable|string|max:50', // VD: ThS.BS
            'license_number' => 'nullable|string|max:50', // Số CCHN
            'license_issued_by' => 'nullable|string|max:255', // Nơi cấp
            'license_image' => 'nullable|image|mimes:jpg,jpeg,png,pdf|max:2048', // Ảnh chứng chỉ
            // --- TÀI CHÍNH (Bỏ hoa hồng thuốc) ---
            'base_salary' => 'nullable|numeric|min:0',
            'commission_exam_percent' => 'nullable|numeric|min:0|max:100',
            // 'commission_prescription_percent' -> ĐÃ BỎ
            // 'commission_service_percent' -> Có thể giữ nếu có dịch vụ khác, ở đây tôi tạm giữ
            // 'commission_service_percent' => 'nullable|numeric|min:0|max:100',
            'max_patients' => 'nullable|integer|min:1',
            'bank_name' => 'nullable|string|max:255',
            'bank_account_number' => 'nullable|string|max:50',
            'bank_account_holder' => 'nullable|string|max:255',
        ]);

        $data['status'] = $request->has('status') ? (bool)$request->input('status') : 0;
        
        // Mặc định giá trị
        $data['base_salary'] = $data['base_salary'] ?? 0;
        $data['commission_exam_percent'] = $data['commission_exam_percent'] ?? 0;
        $data['commission_prescription_percent'] = 0; // Luôn bằng 0
        $data['commission_service_percent'] = $data['commission_service_percent'] ?? 0;
        // Set mặc định nếu không nhập
        $data['max_patients'] = $data['max_patients'] ?? 20;
        DB::beginTransaction();
        try {
            if ($request->hasFile('image')) {
                $data['image'] = $request->file('image')->store('uploads/doctors', 'public');
            }
            if ($request->hasFile('license_image')) {
                $data['license_image'] = $request->file('license_image')->store('uploads/certificates', 'public');
            }
            DoctorSite::create($data);

            DB::commit();
            return redirect()->route('doctorsite.index')->with('success', 'Thêm hồ sơ bác sĩ thành công!');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Lỗi: '.$e->getMessage());
        }
    }

    public function edit(DoctorSite $doctor)
    {
        $departments = Department::all();
        return view('doctorsite.edit', compact('doctor', 'departments'));
    }

    public function update(Request $request, DoctorSite $doctor)
    {
        $data = $request->validate([
            'department_id' => 'nullable|exists:departments,id',
            'specialization' => 'nullable|string|max:255',
            'bio' => 'nullable|string',
            'experience_years' => 'nullable|integer|min:0',
            'status' => 'sometimes|boolean',
            // --- THÔNG TIN UY TÍN (MỚI) ---
            'degree' => 'nullable|string|max:50',
            'license_number' => 'nullable|string|max:50',
            'license_issued_by' => 'nullable|string|max:255',
            'license_image' => 'nullable|image|mimes:jpg,jpeg,png,pdf|max:2048',
            // Tài chính (Bỏ hoa hồng thuốc)
            'base_salary' => 'nullable|numeric|min:0',
            'commission_exam_percent' => 'nullable|numeric|min:0|max:100',
            'commission_service_percent' => 'nullable|numeric|min:0|max:100',
            'max_patients' => 'nullable|integer|min:1',
            'bank_name' => 'nullable|string|max:255',
            'bank_account_number' => 'nullable|string|max:50',
            'bank_account_holder' => 'nullable|string|max:255',

            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$doctor->user_id,
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        DB::beginTransaction();
        try {
            $doctor->user->update([
                'name' => $data['name'],
                'email' => $data['email'],
            ]);

            $doctorData = [
                'department_id' => $data['department_id'] ?? null,
                'specialization' => $data['specialization'] ?? null,
                'bio' => $data['bio'] ?? null,
                'experience_years' => $data['experience_years'] ?? 0,
                'status' => $request->has('status') ? (bool)$request->input('status') : 0,
                // Update Uy tín
                'degree' => $data['degree'] ?? null,
                'license_number' => $data['license_number'] ?? null,
                'license_issued_by' => $data['license_issued_by'] ?? null,
                // Tài chính update
                'base_salary' => $data['base_salary'] ?? 0,
                'commission_exam_percent' => $data['commission_exam_percent'] ?? 0,
                // Thuốc giữ nguyên cũ hoặc set 0
              // Các trường khác set 0
                'commission_prescription_percent' => 0, 
                'commission_service_percent' => 0,
                'max_patients' => $data['max_patients'] ?? 20,
                'bank_name' => $data['bank_name'] ?? null,
                'bank_account_number' => $data['bank_account_number'] ?? null,
                'bank_account_holder' => $data['bank_account_holder'] ?? null,
            ];

            if ($request->hasFile('image')) {
                if ($doctor->image) Storage::disk('public')->delete($doctor->image);
                $doctorData['image'] = $request->file('image')->store('uploads/doctors', 'public');
            }
// Xử lý upload ảnh chứng chỉ (MỚI)
            if ($request->hasFile('license_image')) {
                if ($doctor->license_image) Storage::disk('public')->delete($doctor->license_image);
                $doctorData['license_image'] = $request->file('license_image')->store('uploads/certificates', 'public');
            }
            $doctor->update($doctorData);

            DB::commit();
            return redirect()->route('doctorsite.index')->with('success', 'Cập nhật thành công!');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Lỗi: '.$e->getMessage());
        }
    }

    // --- HÀM TÍNH LƯƠNG (ĐÃ SỬA: LƯƠNG CỨNG CỐ ĐỊNH + HOA HỒNG) ---
    public function finance(Request $request, DoctorSite $doctor)
    {
        $month = $request->input('month', Carbon::now()->month);
        $year = $request->input('year', Carbon::now()->year);

        // 1. THỐNG KÊ HIỆU SUẤT (Chỉ để xem, không ảnh hưởng tiền lương)
        // Tổng giờ online
        $actualHours = DoctorAttendance::where('doctor_id', $doctor->user_id)
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->sum('total_hours');
            
        // Số ngày có hoạt động
        $activeDays = DoctorAttendance::where('doctor_id', $doctor->user_id)
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->distinct('date')
            ->count();

        // 2. TÍNH LƯƠNG CỨNG (CỐ ĐỊNH)
        // Lấy nguyên lương cứng đã set, không trừ
        $fixedSalary = $doctor->base_salary; 

        // 3. TÍNH HOA HỒNG KHÁM BỆNH
        $completedAppointments = Appointment::where('doctor_id', $doctor->user_id)
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->where('status', 'Hoàn thành')
            ->get();

        $examFee = $doctor->department ? $doctor->department->fee : 200000; 
        $totalExamRevenue = $completedAppointments->count() * $examFee;
        $commissionExam = $totalExamRevenue * ($doctor->commission_exam_percent / 100);

        // 4. TỔNG THU NHẬP
        $totalIncome = $fixedSalary + $commissionExam;

        return view('doctorsite.finance', compact(
            'doctor', 'month', 'year', 'totalIncome',
            'actualHours', 'activeDays', 'fixedSalary',
            'completedAppointments', 'examFee', 'totalExamRevenue', 'commissionExam'
        ));
    }

    public function destroy(DoctorSite $doctor)
    {
        if ($doctor->image) Storage::disk('public')->delete($doctor->image);
        $doctor->delete();
        return redirect()->route('doctorsite.index')->with('success', 'Đã xóa bác sĩ!');
    }
    
     public function show(DoctorSite $doctor)
    {
        return view('doctorsite.show', compact('doctor'));
    }
}