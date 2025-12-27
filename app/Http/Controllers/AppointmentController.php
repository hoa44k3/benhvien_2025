<?php

namespace App\Http\Controllers;

use App\Helpers\AuditHelper;
use App\Models\Appointment;
use App\Models\User;
use App\Models\Staff;
use App\Models\Department;
use App\Models\MedicalRecord;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth; 
use App\Mail\AppointmentConfirmationMail;
use Illuminate\Support\Facades\Mail;
use App\Models\Role;

class AppointmentController extends Controller
{
    public function index(Request $request)
    {
        // 1. Khởi tạo Query
        $query = Appointment::with(['doctor', 'department', 'user', 'approver', 'checkinUser']);
        // Tìm theo từ khóa (Mã, Tên BN, SĐT)
        if ($request->filled('keyword')) {
            $kw = $request->keyword;
            $query->where(function($q) use ($kw) {
                $q->where('code', 'like', "%$kw%")
                ->orWhere('patient_name', 'like', "%$kw%")
                ->orWhere('patient_phone', 'like', "%$kw%");
            });
        }

        // Lọc theo Ngày (Mặc định là hôm nay nếu không chọn gì, hoặc chọn 'all' để xem hết)
        if ($request->filled('date')) {
            $query->whereDate('date', $request->date);
        } 
        // Mẹo: Nếu bạn muốn mặc định vào là hiện lịch hôm nay luôn thì mở comment dòng dưới:
        // else { $query->whereDate('date', now()); }

        // Lọc theo Trạng thái
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Lọc theo Bác sĩ
        if ($request->filled('doctor_id')) {
            $query->where('doctor_id', $request->doctor_id);
        }

        // 3. Sắp xếp: Ưu tiên Ngày giảm dần -> Giờ tăng dần
        $query->orderBy('date', 'desc')->orderBy('time', 'asc');

        // 4. Phân trang
        $appointments = $query->paginate(15)->withQueryString(); // withQueryString để giữ tham số lọc khi chuyển trang
// 2. LẤY DANH SÁCH NHÂN VIÊN THEO ROLE (CẬP NHẬT MỚI)
    
    // Lấy Bác sĩ (Role = doctor)
    // Cách viết này an toàn nếu bạn dùng bảng roles riêng như trong DoctorSiteController
    $doctorRole = Role::where('name', 'doctor')->first();
    $doctors = $doctorRole ? $doctorRole->users : collect();

    // Lấy Lễ tân (Role = receptionist) -> BIẾN NÀY ĐANG THIẾU
    // $recepRole = Role::where('name', 'receptionist')->first();
    $receptionists = User::whereHas('roles', fn($q) => $q->where('name', 'receptionist'))->get();

    // Lấy Y tá (Role = nurse) -> Dùng cho check-in
    // $nurseRole = Role::where('name', 'nurse')->first();
  $nurses = User::whereHas('roles', fn($q) => $q->where('name', 'nurse'))->get();
        // Lấy danh sách bác sĩ để đổ vào Select Box lọc
        // $doctors = User::whereHas('roles', fn($q) => $q->where('name', 'doctor'))->get();

        return view('appointments.index', compact('appointments', 'doctors', 'receptionists', 'nurses'));
    }
    public function create()
    {
        $doctors = User::whereHas('roles', fn($q) => $q->where('name','doctor'))->get();

        $departments = Department::all();
        $users = User::all();

        return view('appointments.create', compact('doctors', 'departments', 'users'));
    }

  public function storeFromSite(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Bạn cần đăng nhập để đặt lịch.');
        }

        $request->validate([
            'department_id' => 'required|exists:departments,id',
            'doctor_id' => 'required|exists:doctor_sites,id', 
            'date' => 'required|date|after_or_equal:today',
            'time' => 'required',
            'patient_name' => 'required|string|max:255',
            'patient_phone' => 'required|string|max:20',
            'reason' => 'nullable|string|max:500',
        ]);
        $doctorSite = \App\Models\DoctorSite::with('user')->find($request->doctor_id);
        if (!$doctorSite || !$doctorSite->user) {
                return back()->with('error','Bác sĩ không hợp lệ.');
            }
            $doctorUserId = $doctorSite->user->id;
    

        $appointment = Appointment::create([
                'code' => 'LH' . strtoupper(uniqid()),
                'user_id' => Auth::id(),              
                'doctor_id' => $doctorUserId,          
                'department_id' => $request->department_id ?? null,
                'patient_name' => Auth::user()->name,  
                'patient_phone' => $request->patient_phone,
                'reason' => $request->reason ?? null,
                'date' => $request->date,
                'time' => $request->time,
                'status' => 'Đang chờ',
                'approved_by' => null,
                'checked_in_by' => null,
            ]);
        AuditHelper::log('Đặt lịch từ site', $appointment->patient_name, 'Thành công');
        // Gửi mail cho chính user đang login (bệnh nhân)
    if (Auth::user()->email) {
        Mail::to(Auth::user()->email)->send(new AppointmentConfirmationMail($appointment));
    }
        return back()->with('success', 'Đặt lịch khám thành công!');
    }

    public function store(Request $request)
    {
        //  dd($request->all());

        try {
            $validated = $request->validate([
                 'patient_name' => 'required|string|max:255',
                    'doctor_id' => 'required|exists:users,id',
                 'department_id' => 'required|exists:departments,id',
                'patient_phone' => 'nullable|string|max:20',
                'doctor_id' => 'required|exists:users,id',
                'department_id' => 'nullable|exists:departments,id',
                'reason' => 'nullable|string',
                'diagnosis' => 'nullable|string',
                'notes' => 'nullable|string',

                'date' => 'required|date',
                'time' => 'required',
                'status' => 'required|in:Đang chờ,Đã xác nhận,Đang khám,Hoàn thành,Đã hẹn,Hủy',
                'approved_by' => 'nullable|exists:users,id',
                'checked_in_by' => 'nullable|exists:users,id',
            ]);
            $user = $request->user(); if (!$user) { return redirect()->back()->with('error', '⚠️ Bạn cần đăng nhập để đặt lịch khám.'); }

            //  Tạo mã bệnh nhân
            // $patientCode = 'BN' . str_pad($user->id, 5, '0', STR_PAD_LEFT);

            // //  Sinh mã lịch hẹn tự động (nếu chưa có)
            // $code = 'LH' . now()->format('YmdHis');
            // Nếu admin tạo hộ bệnh nhân vãng lai (không có tài khoản), ta có thể để user_id là null hoặc lấy ID của admin
            // Nhưng patient_name phải lấy từ FORM nhập vào ($validated['patient_name'])
            $code = 'LH' . now()->format('YmdHis');
            $patientCode = 'BN' . str_pad($user->id, 5, '0', STR_PAD_LEFT);
            $appointment = Appointment::create([
                 'code' => $code,
                'user_id' => $user->id,
                'doctor_id' => $validated['doctor_id'],
                'department_id' => $validated['department_id'] ?? null,
                 'patient_code' => $patientCode,
            //   'patient_name' => Auth::user()->name,
                'patient_name' => $validated['patient_name'],
                'patient_phone' => $validated['patient_phone'] ,
                'patient_code' => 'BN_GUEST', // Hoặc logic sinh mã riêng cho khách vãng lai
                'reason' => $validated['reason'] ?? null,
                'diagnosis' => $validated['diagnosis'] ?? null,
                'notes' => $validated['notes'] ?? null,
                'date' => $validated['date'],
                'time' => $validated['time'],

                'status' => $validated['status'],
              'approved_by' => null,

                'checked_in_by' => $validated['checked_in_by'],
            ]);

            AuditHelper::log('Thêm lịch hẹn', $appointment->patient_name, 'Thành công');
            // Nếu user có email thì gửi
                $patientUser = User::find($user->id); 
                if ($patientUser && $patientUser->email) {
                    Mail::to($patientUser->email)->send(new AppointmentConfirmationMail($appointment));
                }
            return redirect()->route('appointments.index')->with('success', ' Thêm lịch hẹn thành công!');
        } catch (\Throwable $e) {
            AuditHelper::log('Thêm lịch hẹn', $request->patient_name ?? 'Không rõ', 'Thất bại');
            return redirect()->back()->with('error', ' Lỗi khi thêm lịch hẹn: ' . $e->getMessage());
        }
    }
    public function edit(Appointment $appointment)
    {
         $doctors = User::whereHas('roles', fn($q) => $q->where('name','doctor'))->get();

        $departments = Department::all();
        $users = User::all();

        return view('appointments.edit', compact('appointment', 'doctors', 'departments', 'users'));
    }
    public function update(Request $request, Appointment $appointment)
    {
        try {
            $validated = $request->validate([
                'patient_name' => 'required|string|max:255',
                'patient_phone' => 'nullable|string|max:20',
                'doctor_id' => 'required|exists:users,id',
                'department_id' => 'nullable|exists:departments,id',
                'reason' => 'nullable|string',
                'diagnosis' => 'nullable|string',
                'notes' => 'nullable|string',
                'date' => 'required|date',
                'time' => 'required',
              'status' => 'required|in:Đang chờ,Đã xác nhận,Đang khám,Hoàn thành,Đã hẹn, Hủy',
            'approved_by' => 'nullable|exists:users,id',
            'checked_in_by' => 'nullable|exists:users,id',      

            ]);

            $appointment->update([
                'patient_name' => $validated['patient_name'],
                'patient_phone' => $validated['patient_phone'] ?? null,
                'doctor_id' => $validated['doctor_id'],
                'department_id' => $validated['department_id'] ?? null,
                'reason' => $validated['reason'] ?? null,
                'diagnosis' => $validated['diagnosis'] ?? null,
                'notes' => $validated['notes'] ?? null,
                'date' => $validated['date'],
               'time' => $validated['time'],

                // 'time' => Carbon::parse($validated['time'])->format('Y-m-d H:i:s'),
                'status' => $validated['status'],
                 //  Nếu admin đổi trạng thái → cập nhật người duyệt
                // 'approved_by' => Auth::id(),

                // //  Nếu trạng thái = Đang khám → tự động check-in
                // 'checked_in_by' => $validated['status'] == 'Đang khám' ? Auth::id() : $appointment->checked_in_by,
                 'approved_by' => $validated['approved_by'] ?? $appointment->approved_by,
            'checked_in_by' => $validated['checked_in_by'] ?? $appointment->checked_in_by,
            ]);

            AuditHelper::log('Cập nhật lịch hẹn', $appointment->patient_name, 'Thành công');

            return redirect()->route('appointments.index')->with('success', '✅ Cập nhật lịch hẹn thành công!');
        } catch (\Throwable $e) {
            AuditHelper::log('Cập nhật lịch hẹn', $appointment->patient_name ?? 'Không rõ', 'Thất bại');
            return redirect()->back()->with('error', '❌ Lỗi khi cập nhật lịch hẹn: ' . $e->getMessage());
        }
    }
   
    // --- CẬP NHẬT: DUYỆT LỊCH (Nhận ID người duyệt từ form popup) ---
    public function approve(Request $request, $id)
    {
        $app = Appointment::findOrFail($id);
        
        // Lấy ID người được chọn từ popup, nếu không chọn thì lấy người đang login
        $approverId = $request->input('approver_id', Auth::id());

        $app->update([
            'status' => 'Đã xác nhận',
            'approved_by' => $approverId,
        ]);

        return back()->with('success', 'Đã duyệt lịch thành công!');
    }

    public function checkIn(Request $request, $id)
    {
        $app = Appointment::findOrFail($id);
        
        // Lấy ID người check-in từ popup
        $checkerId = $request->input('checked_in_by', Auth::id());

        // 1. Tạo Medical Record (Hồ sơ bệnh án)
        MedicalRecord::firstOrCreate(
            ['appointment_id' => $app->id],
            [
                'user_id' => $app->user_id,
                'doctor_id' => $app->doctor_id,
                'department_id' => $app->department_id,
                'title' => 'Khám bệnh ngày ' . now()->format('d/m/Y'),
                'date' => $app->date ?? now()->toDateString(),
                
                // --- SỬA LỖI TẠI ĐÂY ---
                // Đổi 'pending' thành 0 (vì cột này trong DB chắc chắn là kiểu số hoặc boolean)
                'status' => '0' 
            ]
        );

        // 2. Cập nhật trạng thái Lịch hẹn -> Đang khám
        $app->update([
            'status' => 'Đang khám',
            'checked_in_by' => $checkerId
        ]);

        return back()->with('success', 'Check-in thành công! Bệnh nhân đã vào hàng chờ.');
    }

    public function confirm(Request $r, Appointment $appointment){
            $appointment->update(['status'=>'confirmed']);
            return back()->with('success','Xác nhận thành công');
        }

        public function cancel(Request $r, Appointment $appointment){
            $appointment->update(['status'=>'cancelled']);
            return back()->with('success','Đã hủy lịch');
        }

        public function destroy(Appointment $appointment)
        {
            try {
                $appointment->delete();
                AuditHelper::log('Xóa lịch hẹn', $appointment->patient_name, 'Thành công');
                return redirect()->route('appointments.index')->with('success', '🗑️ Đã xóa lịch hẹn thành công!');
            } catch (\Throwable $e) {
                AuditHelper::log('Xóa lịch hẹn', $appointment->patient_name ?? 'Không rõ', 'Thất bại');
                return redirect()->back()->with('error', '❌ Không thể xóa: ' . $e->getMessage());
            }
        }

        public function show(Appointment $appointment)
        {
            return view('appointments.show', compact('appointment'));
        }
    }
