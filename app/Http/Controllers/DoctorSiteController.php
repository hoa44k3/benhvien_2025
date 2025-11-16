<?php
namespace App\Http\Controllers;

use App\Models\DoctorSite;
use App\Models\User;
use App\Models\Department;
use Illuminate\Http\Request;

class DoctorSiteController extends Controller
{
    public function index()
{
    $doctors = DoctorSite::with('user')
    ->whereHas('user.roles', function ($q) {
        $q->where('name', 'doctor');
    })
    ->paginate(10);


    return view('doctorsite.index', compact('doctors'));
}


    public function create()
    {
        $users = User::where('role', 'doctor')->get();
        $departments = Department::all();
        return view('doctorsite.create', compact('users', 'departments'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'department_id' => 'nullable|exists:departments,id',
            'specialty' => 'required|string|max:255',
            'bio' => 'nullable|string',
            'rating' => 'nullable|numeric|min:0|max:5',
            'reviews_count' => 'nullable|integer|min:0',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'status' => 'boolean'
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('uploads/doctors', 'public');
        }

        DoctorSite::create($data);

        return redirect()->route('doctorsite.index')->with('success', 'Thêm bác sĩ thành công!');
    }

    public function edit(DoctorSite $doctor)
    {
        $users = User::where('role', 'doctor')->get();
        $departments = Department::all();
        return view('doctorsite.edit', compact('doctor', 'users', 'departments'));
    }

    public function update(Request $request, DoctorSite $doctor)
    {
        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'department_id' => 'nullable|exists:departments,id',
            'specialty' => 'required|string|max:255',
            'bio' => 'nullable|string',
            'rating' => 'nullable|numeric|min:0|max:5',
            'reviews_count' => 'nullable|integer|min:0',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'status' => 'boolean'
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('uploads/doctors', 'public');
        }

        $doctor->update($data);

        return redirect()->route('doctorsite.index')->with('success', 'Cập nhật thông tin bác sĩ thành công!');
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


