<?php
namespace App\Http\Controllers;

use App\Models\DoctorSite;
use App\Models\User;
use App\Models\Department;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class DoctorSiteController extends Controller
{

    public function index()
{
    // Lấy danh sách bác sĩ, eager load user và department
    $doctors = DoctorSite::with('user', 'department')
        ->paginate(10);

    return view('doctorsite.index', compact('doctors'));
}

    public function create()
    {
    $doctorRole = Role::where('name', 'doctor')->first();
    $users = $doctorRole ? $doctorRole->users : collect();
    $departments = Department::all();

    return view('doctorsite.create', compact('users', 'departments'));
    }

    // Lưu bác sĩ mới
    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'department_id' => 'nullable|exists:departments,id',
            'specialization' => 'nullable|string|max:255',
            'bio' => 'nullable|string',
            'experience_years' => 'nullable|integer|min:0',
            'rating' => 'nullable|numeric|min:0|max:5',
            'review_count' => 'nullable|integer|min:0',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'status' => 'sometimes|boolean'
        ]);

        // đảm bảo key status luôn có: nếu checkbox không gửi -> mặc định 0
        $data['status'] = $request->has('status') ? (bool)$request->input('status') : 0;

        DB::beginTransaction();
        try {
            if ($request->hasFile('image')) {
                $data['image'] = $request->file('image')->store('uploads/doctors', 'public');
                Log::info('[DoctorSite][store] image stored', ['path' => $data['image']]);
            }

            Log::info('[DoctorSite][store] validated data', $data);

            $doctor = DoctorSite::create($data);

            Log::info('[DoctorSite][store] created', $doctor->toArray());

            DB::commit();
            return redirect()->route('doctorsite.index')->with('success', 'Thêm bác sĩ thành công!');
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('[DoctorSite][store] error: '.$e->getMessage(), [
                'exception' => $e, 'request' => $request->all()
            ]);
            return back()->withInput()->with('error', 'Lỗi khi thêm bác sĩ: '.$e->getMessage());
        }
    }
    public function edit(DoctorSite $doctor)
    {
         $doctorRole = Role::where('name', 'doctor')->first();
        $users = $doctorRole ? $doctorRole->users : collect();
        $departments = Department::all();

    return view('doctorsite.edit', compact('doctor', 'users', 'departments'));
    }

    
     // Cập nhật bác sĩ
    // public function update(Request $request, DoctorSite $doctor)
    // {
    //     $data = $request->validate([
    //         'user_id' => 'required|exists:users,id',
    //         'department_id' => 'nullable|exists:departments,id',
    //         'specialization' => 'nullable|string|max:255',
    //         'bio' => 'nullable|string',
    //         'experience_years' => 'nullable|integer|min:0',
    //         'rating' => 'nullable|numeric|min:0|max:5',
    //         'review_count' => 'nullable|integer|min:0',
    //         'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
    //         'status' => 'sometimes|boolean'
    //     ]);

    //     $data['status'] = $request->has('status') ? (bool)$request->input('status') : 0;

    //     DB::beginTransaction();
    //     try {
    //         Log::info('[DoctorSite][update] validated', $data);

    //         if ($request->hasFile('image')) {
    //             if ($doctor->image) {
    //                 Storage::disk('public')->delete($doctor->image);
    //                 Log::info('[DoctorSite][update] deleted old image', ['old' => $doctor->image]);
    //             }
    //             $data['image'] = $request->file('image')->store('uploads/doctors', 'public');
    //             Log::info('[DoctorSite][update] new image stored', ['path' => $data['image']]);
    //         }

    //         $doctor->update($data);

    //         Log::info('[DoctorSite][update] updated', $doctor->fresh()->toArray());

    //         DB::commit();
    //         return redirect()->route('doctorsite.index')->with('success', 'Cập nhật thông tin bác sĩ thành công!');
    //     } catch (\Throwable $e) {
    //         DB::rollBack();
    //         Log::error('[DoctorSite][update] error: '.$e->getMessage(), [
    //             'exception' => $e, 'doctor_id' => $doctor->id, 'request' => $request->all()
    //         ]);
    //         return back()->withInput()->with('error', 'Lỗi khi cập nhật bác sĩ: '.$e->getMessage());
    //     }
    // }
   public function update(Request $request, DoctorSite $doctor)
{
    $data = $request->validate([
        // DoctorSite
        'department_id' => 'nullable|exists:departments,id',
        'specialization' => 'nullable|string|max:255',
        'bio' => 'nullable|string',
        'experience_years' => 'nullable|integer|min:0',
        'rating' => 'nullable|numeric|min:0|max:5',
        'review_count' => 'nullable|integer|min:0',
        'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        'status' => 'sometimes|boolean',
        // User
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email,'.$doctor->user_id,
    ]);

    DB::beginTransaction();
    try {
        // 1. Cập nhật user
        $doctor->user->update([
            'name' => $data['name'],
            'email' => $data['email'],
        ]);

        // 2. Cập nhật doctor_sites
        $doctorData = [
            'department_id' => $data['department_id'] ?? null,
            'specialization' => $data['specialization'] ?? null,
            'bio' => $data['bio'] ?? null,
            'experience_years' => $data['experience_years'] ?? 0,
            'rating' => $data['rating'] ?? 0,
            'review_count' => $data['review_count'] ?? 0,
            'status' => $request->has('status') ? (bool)$request->input('status') : 0,
        ];

        if ($request->hasFile('image')) {
            if ($doctor->image) Storage::disk('public')->delete($doctor->image);
            $doctorData['image'] = $request->file('image')->store('uploads/doctors', 'public');
        }

        $doctor->update($doctorData);

        DB::commit();
        return redirect()->route('doctorsite.index')->with('success', 'Cập nhật bác sĩ thành công!');
    } catch (\Throwable $e) {
        DB::rollBack();
        return back()->withInput()->with('error', 'Lỗi khi cập nhật bác sĩ: '.$e->getMessage());
    }
}




    public function destroy(DoctorSite $doctor)
    {
        $doctor->delete();
        return redirect()->route('doctorsite.index')->with('success', 'Đã xóa bác sĩ thành công!');
    }

    public function show(DoctorSite $doctor)
    {
        return view('doctorsite.show', compact('doctor'));
    }
}


