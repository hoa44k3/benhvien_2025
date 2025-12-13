<?php

use App\Http\Controllers\Admincontroller;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\NurseController;
use App\Http\Controllers\PharmacistController;
use App\Http\Controllers\ReceptionistController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\MedicineController;
use App\Http\Controllers\HospitalRoomController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ClinicalExamController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\DoctorScheduleController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DoctorDiagnosisController;
use App\Http\Controllers\DoctorPatientController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\MedicalRecordController;
use App\Http\Controllers\DoctorSiteController;
use App\Http\Controllers\PrescriptionController;
use App\Http\Controllers\PrescriptionItemController;
use App\Http\Controllers\TestResultController;
use App\Http\Controllers\InvoiceController;

Route::group(['prefix' => ''], function() {
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/services', [HomeController::class, 'services'])->name('services');
    Route::get('/services/{service}', [HomeController::class, 'serviceShow'])->name('services.show');
    Route::get('/schedule', [HomeController::class, 'schedule'])->name('schedule');
    Route::post('/schedule', [HomeController::class, 'storeFromSite'])->name('site.schedule.store');
    Route::get('/my-appointments', [HomeController::class, 'myAppointments'])->name('site.my_appointments');
    Route::get('/payment', [HomeController::class, 'payment'])->name('payment');
    Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
    Route::get('/medical_records', [HomeController::class, 'medical_records'])
    ->name('medical_records')
    ->middleware('auth');

});


Route::prefix('appointments')->group(function () {
    Route::get('/', [AppointmentController::class, 'index'])->name('appointments.index');
    Route::get('/create', [AppointmentController::class, 'create'])->name('appointments.create');
    Route::post('/store', [AppointmentController::class, 'store'])->name('appointments.store');
    Route::get('/{appointment}/edit', [AppointmentController::class, 'edit'])->name('appointments.edit');
    Route::put('/{appointment}', [AppointmentController::class, 'update'])->name('appointments.update');
    Route::delete('/{appointment}', [AppointmentController::class, 'destroy'])->name('appointments.destroy');
  Route::post('/{id}/approve', [AppointmentController::class, 'approve'])
        ->name('appointments.approve')
        ->middleware('auth');
Route::post('/appointments/{id}/check-in', 
    [AppointmentController::class, 'checkIn'])
    ->name('appointments.checkin');


});

// Route::prefix('admin/medical_records')->group(function () {
//     Route::get('/', [MedicalRecordController::class, 'index'])->name('medical_records.index');
//     Route::get('/create', [MedicalRecordController::class, 'create'])->name('medical_records.create');
//     Route::post('/store', [MedicalRecordController::class, 'store'])->name('medical_records.store');
//     Route::get('/{medical_record}', [MedicalRecordController::class, 'show'])->name('medical_records.show'); // đây là show
//     Route::get('/{medical_record}/edit', [MedicalRecordController::class, 'edit'])->name('medical_records.edit');
//     Route::put('/{medical_record}', [MedicalRecordController::class, 'update'])->name('medical_records.update');
//     Route::delete('/{medical_record}', [MedicalRecordController::class, 'destroy'])->name('medical_records.destroy');
//     Route::post('/{medical_record}/complete', [MedicalRecordController::class, 'complete'])
// ->name('medical_records.complete');
// Route::get('/download/{id}', [MedicalRecordController::class, 'download'])->name('medical_records.download');
// });

Route::prefix('admin/medical_records')->group(function () {
    Route::get('/', [MedicalRecordController::class, 'index'])->name('medical_records.index');
    Route::get('/create', [MedicalRecordController::class, 'create'])->name('medical_records.create');
    Route::post('/store', [MedicalRecordController::class, 'store'])->name('medical_records.store');
    Route::get('/{medical_record}', [MedicalRecordController::class, 'show'])->name('medical_records.show');
    Route::get('/{medical_record}/edit', [MedicalRecordController::class, 'edit'])->name('medical_records.edit');
    Route::put('/{medical_record}', [MedicalRecordController::class, 'update'])->name('medical_records.update');
    Route::delete('/{medical_record}', [MedicalRecordController::class, 'destroy'])->name('medical_records.destroy');
    Route::post('/{medical_record}/complete', [MedicalRecordController::class, 'complete'])->name('medical_records.complete');
    Route::get('/download/{id}', [MedicalRecordController::class, 'download'])->name('medical_records.download');
    // Route Bắt đầu khám
    Route::post('/{medical_record}/start', [MedicalRecordController::class, 'startExam'])->name('medical_records.start');
    
    // Route Hủy khám
    Route::post('/{medical_record}/cancel', [MedicalRecordController::class, 'cancel'])->name('medical_records.cancel');
});


Route::prefix('invoices')->group(function () {
    Route::get('/', [InvoiceController::class, 'index'])->name('invoices.index');
    Route::get('/create', [InvoiceController::class, 'create'])->name('invoices.create');
    Route::post('/store', [InvoiceController::class, 'store'])->name('invoices.store');
    Route::get('/{invoice}', [InvoiceController::class, 'show'])->name('invoices.show');
    Route::get('/{invoice}/edit', [InvoiceController::class, 'edit'])->name('invoices.edit');
    Route::put('/{invoice}', [InvoiceController::class, 'update'])->name('invoices.update');
    Route::delete('/{invoice}', [InvoiceController::class, 'destroy'])->name('invoices.destroy');
});

Route::prefix('prescriptions')->group(function () {

    // LIST + CREATE + STORE
    Route::get('/', [PrescriptionController::class, 'index'])->name('prescriptions.index');
    Route::get('/create', [PrescriptionController::class, 'create'])->name('prescriptions.create');
    Route::post('/store', [PrescriptionController::class, 'store'])->name('prescriptions.store');

    // SHOW + EDIT + UPDATE
    Route::get('/{prescription}', [PrescriptionController::class, 'show'])->name('prescriptions.show');
    Route::get('/{prescription}/edit', [PrescriptionController::class, 'edit'])->name('prescriptions.edit');
    Route::put('/{prescription}', [PrescriptionController::class, 'update'])->name('prescriptions.update');

    // DELETE
    Route::delete('/{prescription}', [PrescriptionController::class, 'destroy'])->name('prescriptions.destroy');
    // Export Excel
    Route::get('/export/excel', [PrescriptionController::class, 'exportExcel'])
        ->name('prescriptions.export');
});
// ITEMS trong đơn thuốc
Route::prefix('prescription-items')->group(function () {

    Route::get('/create/{prescription}', 
        [PrescriptionItemController::class, 'create'])
        ->name('prescription_items.create');

    Route::post('/store/{prescription}', 
        [PrescriptionItemController::class, 'store'])
        ->name('prescription_items.store');

    Route::get('/{item}/edit', 
        [PrescriptionItemController::class, 'edit'])
        ->name('prescription_items.edit');

    Route::put('/{item}', 
        [PrescriptionItemController::class, 'update'])
        ->name('prescription_items.update');

    Route::delete('/{item}', 
        [PrescriptionItemController::class, 'destroy'])
        ->name('prescription_items.destroy');
});

Route::prefix('test_results')->group(function () {
    Route::get('/', [TestResultController::class, 'index'])->name('test_results.index');
    Route::get('/create', [TestResultController::class, 'create'])->name('test_results.create');
    Route::post('/store', [TestResultController::class, 'store'])->name('test_results.store');

    Route::get('/{testResult}', [TestResultController::class, 'show'])->name('test_results.show');
    Route::get('/{testResult}/edit', [TestResultController::class, 'edit'])->name('test_results.edit');
    Route::put('/{testResult}', [TestResultController::class, 'update'])->name('test_results.update');

    Route::delete('/{testResult}', [TestResultController::class, 'destroy'])->name('test_results.destroy');
});
Route::resource('medical_record_files', App\Http\Controllers\MedicalRecordFileController::class);



Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', [Admincontroller::class, 'index'])->name('admin.index');
});

Route::middleware(['auth', 'nurse'])->group(function () {
    Route::get('/nurse', [NurseController::class, 'index'])->name('nurse.index');
});
Route::middleware(['auth', 'pharmacist'])->group(function () {
    Route::get('/pharmacist', [PharmacistController::class, 'index'])->name('pharmacist.index');
});

Route::middleware(['auth', 'receptionist'])->group(function () {
    Route::get('/receptionist', [ReceptionistController::class, 'index'])->name('receptionist.index');
});

// Đăng nhập
Route::get('/login', [AuthController::class, 'loginForm'])->name('login.form');
Route::post('/login', [AuthController::class, 'login'])->name('login');

// Đăng ký
Route::get('/register', [AuthController::class, 'registerForm'])->name('register.form');
Route::post('/register', [AuthController::class, 'register'])->name('register');


Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect()->route('home');
})->name('logout');


// Route::middleware(['auth'])->group(function () {
//     Route::resource('users', UserController::class);
// });
Route::middleware(['auth'])->group(function () {
    Route::get('/home', [UserController::class, 'home'])->name('home');
});



 // Quản lý người dùng
Route::prefix('users')->group(function () {
    Route::get('/', [UserController::class, 'index'])->name('users.index');
    Route::get('/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/store', [UserController::class, 'store'])->name('users.store');
    Route::get('/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/{user}', [UserController::class, 'destroy'])->name('users.destroy');
});

Route::prefix('departments')->group(function () {
    Route::get('/', [DepartmentController::class, 'index'])->name('departments.index');
    Route::get('/create', [DepartmentController::class, 'create'])->name('departments.create');
    Route::post('/store', [DepartmentController::class, 'store'])->name('departments.store');
    Route::get('/{department}', [DepartmentController::class, 'show'])->name('departments.show');
    Route::get('/{department}/edit', [DepartmentController::class, 'edit'])->name('departments.edit');
    Route::put('/{department}', [DepartmentController::class, 'update'])->name('departments.update');
    Route::delete('/{department}', [DepartmentController::class, 'destroy'])->name('departments.destroy');
});

Route::prefix('medicines')->group(function () {
    Route::get('/', [MedicineController::class, 'index'])->name('medicines.index');
    Route::get('/create', [MedicineController::class, 'create'])->name('medicines.create');
    Route::post('/store', [MedicineController::class, 'store'])->name('medicines.store');
    Route::get('/{medicine}/edit', [MedicineController::class, 'edit'])->name('medicines.edit');
    Route::put('/{medicine}', [MedicineController::class, 'update'])->name('medicines.update');
    Route::delete('/{medicine}', [MedicineController::class, 'destroy'])->name('medicines.destroy');
});

Route::prefix('categories')->group(function () {
    Route::get('/', [CategoryController::class, 'index'])->name('categories.index');
    Route::get('/create', [CategoryController::class, 'create'])->name('categories.create');
    Route::post('/store', [CategoryController::class, 'store'])->name('categories.store');
    Route::get('/{categories}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
    Route::put('/{categories}', [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/{categories}', [CategoryController::class, 'destroy'])->name('categories.destroy');
});

Route::prefix('admin/doctorsite')->group(function () {
    Route::get('/', [DoctorSiteController::class, 'index'])->name('doctorsite.index');
    Route::get('/create', [DoctorSiteController::class, 'create'])->name('doctorsite.create');
    Route::post('/store', [DoctorSiteController::class, 'store'])->name('doctorsite.store');
    Route::get('/edit/{doctor}', [DoctorSiteController::class, 'edit'])->name('doctorsite.edit');
    Route::get('/{doctor}', [DoctorSiteController::class, 'show'])->name('doctorsite.show');
    Route::put('/update/{doctor}', [DoctorSiteController::class, 'update'])->name('doctorsite.update');
    Route::delete('/destroy/{doctor}', [DoctorSiteController::class, 'destroy'])->name('doctorsite.destroy');
});

Route::prefix('admin/services')->group(function () {
    Route::get('/', [ServiceController::class, 'index'])->name('services.index');
    Route::get('/create', [ServiceController::class, 'create'])->name('services.create');
    Route::post('/store', [ServiceController::class, 'store'])->name('services.store');
    Route::get('/edit/{service}', [ServiceController::class, 'edit'])->name('services.edit');
    Route::get('/{service}/show', [ServiceController::class, 'show'])
    ->name('services.show');

    Route::put('/update/{service}', [ServiceController::class, 'update'])->name('services.update');
    Route::delete('/destroy/{service}', [ServiceController::class, 'destroy'])->name('services.destroy');
});



Route::prefix('hospital_rooms')->group(function () {
    Route::get('/', [HospitalRoomController::class, 'index'])->name('hospital_rooms.index');
    Route::get('/create', [HospitalRoomController::class, 'create'])->name('hospital_rooms.create');
    Route::post('/store', [HospitalRoomController::class, 'store'])->name('hospital_rooms.store');
    Route::get('/{hospital_room}/edit', [HospitalRoomController::class, 'edit'])->name('hospital_rooms.edit');
    Route::put('/{hospital_room}', [HospitalRoomController::class, 'update'])->name('hospital_rooms.update');
    Route::delete('/{hospital_room}', [HospitalRoomController::class, 'destroy'])->name('hospital_rooms.destroy');
});

Route::prefix('staff')->name('staff.')->group(function () {
    Route::get('/', [StaffController::class, 'index'])->name('index');          // Danh sách nhân viên
    Route::get('/create', [StaffController::class, 'create'])->name('create');  // Form thêm nhân viên
    Route::post('/', [StaffController::class, 'store'])->name('store');         // Xử lý thêm
    Route::get('/{staff}/edit', [StaffController::class, 'edit'])->name('edit');// Form sửa nhân viên
    Route::put('/{staff}', [StaffController::class, 'update'])->name('update'); // Xử lý sửa
    Route::delete('/{staff}', [StaffController::class, 'destroy'])->name('destroy'); // Xóa nhân viên
});



Route::prefix('audit-log')->name('audit_log.')->group(function () {
    Route::get('/', [AuditLogController::class, 'index'])->name('index');
    
});

Route::prefix('dashboard')->name('dashboard.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('index');
    
});



Route::prefix('reports')->group(function () {
    Route::get('/', [ReportController::class, 'index'])->name('reports.index');
    Route::post('/view', [ReportController::class, 'viewReport'])->name('reports.view');
    Route::get('/export', [ReportController::class, 'exportPDF'])->name('reports.export');
});
Route::middleware(['auth'])->prefix('doctor')->group(function () {
    // 🔹 Trang mặc định: chuyển đến lịch khám
    Route::get('/', function () {
        return redirect()->route('doctor.schedule.index');
    })->name('doctor.index');

    // 🔹 Các route khác
    Route::get('/schedule', [DoctorScheduleController::class, 'index'])->name('doctor.schedule.index');
    Route::get('/schedule/create', [DoctorScheduleController::class, 'create'])->name('doctor.schedule.create');
    Route::post('/schedule/store', [DoctorScheduleController::class, 'store'])->name('doctor.schedule.store');
    Route::put('/schedule/update-shift', [DoctorScheduleController::class, 'updateShift'])->name('doctor.schedule.updateShift');
    Route::put('/schedule/{appointment}/status', [DoctorScheduleController::class, 'updateAppointmentStatus'])->name('doctor.schedule.updateStatus');

    Route::get('/patients', [DoctorPatientController::class, 'index'])->name('doctor.patients.index');
    Route::get('/patients/{id}', [DoctorPatientController::class, 'show'])->name('doctor.patients.show');
    Route::get('/patients/{id}/edit', [DoctorPatientController::class, 'edit'])->name('doctor.patients.edit');
    Route::put('/patients/{id}', [DoctorPatientController::class, 'update'])->name('doctor.patients.update');
    Route::delete('/patients/{id}', [DoctorPatientController::class, 'destroy'])->name('doctor.patients.destroy');

    Route::get('/diagnostic', [DoctorDiagnosisController::class, 'index'])->name('diagnosis.index');
    Route::get('/diagnostic/{id}', [DoctorDiagnosisController::class, 'show'])->name('diagnosis.show');
    Route::post('/diagnostic/{id}', [DoctorDiagnosisController::class, 'store'])->name('diagnosis.store');
    Route::get('/video-call/{id}', [DoctorDiagnosisController::class, 'videoCall'])->name('videoCall');
    Route::get('/diagnosis/{appointment}/prescription', [DoctorDiagnosisController::class, 'viewPrescription'])->name('diagnosis.prescription');
});


Route::prefix('clinical-exams')->name('clinical_exams.')->group(function() {
    Route::get('/', [ClinicalExamController::class,'index'])->name('index');
    Route::get('/create', [ClinicalExamController::class,'create'])->name('create');
    Route::post('/', [ClinicalExamController::class,'store'])->name('store');
    Route::get('/{clinicalExam}/edit', [ClinicalExamController::class,'edit'])->name('edit');
    Route::put('/{clinicalExam}', [ClinicalExamController::class,'update'])->name('update');
    Route::delete('/{clinicalExam}', [ClinicalExamController::class,'destroy'])->name('destroy');
    Route::get('/{clinicalExam}', [ClinicalExamController::class,'show'])->name('show');
});
// Quản lý Kết quả Khám lâm sàng
    Route::resource('clinical_exams', ClinicalExamController::class);
// Clinical exams
// Route::resource('clinical_exams', \App\Http\Controllers\ClinicalExamController::class);

// Invoices
Route::resource('invoices', \App\Http\Controllers\InvoiceController::class);

// Follow ups
Route::resource('follow_ups', \App\Http\Controllers\FollowUpController::class);
// Route tạo hóa đơn từ đơn thuốc
    Route::post('/invoices/create-from-prescription/{id}', [App\Http\Controllers\InvoiceController::class, 'createFromPrescription'])
        ->name('invoices.createFromPrescription');
