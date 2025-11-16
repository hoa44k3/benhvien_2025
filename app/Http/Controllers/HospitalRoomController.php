<?php

namespace App\Http\Controllers;

use App\Helpers\AuditHelper;
use App\Models\Department;
use App\Models\HospitalRoom;
use App\Models\User;
use Illuminate\Http\Request;

class HospitalRoomController extends Controller
{
    public function index()
    {
        $rooms = HospitalRoom::with('department')->paginate(10);
        return view('hospital_rooms.index', compact('rooms'));
    }

    public function create()
    {
        $departments = Department::all();
        $users = User::all();
        return view('hospital_rooms.create', compact('departments', 'users'));
    }

    public function store(Request $request)
{
    try{
         $validated = $request->validate([
        'department_id' => 'required|exists:departments,id',
        'room_code' => 'required|string|max:20|unique:hospital_rooms,room_code',
        'room_type' => 'required|string|max:100',
        'total_beds' => 'required|integer|min:1',
        'occupied_beds' => 'nullable|integer|min:0',
        'status' => 'nullable|in:available,in_use,cleaning,maintenance',
        'user_ids' => 'nullable|array',
        'user_ids.*' => 'exists:users,id',
    ]);

    HospitalRoom::create($validated);
 // 🔹 Ghi log thành công
                AuditHelper::log('Cập nhật thông tin nhân viên', $request->name, 'Thành công');
    return redirect()->route('hospital_rooms.index')->with('success', 'Thêm phòng bệnh thành công!');
    }catch(\Exception $e){
                // 🔹 Ghi log thất bại
                AuditHelper::log('Cập nhật thông tin nhân viên', $rooms->name ?? 'Không rõ', 'Thất bại');
            return redirect()->back()->with('error', 'Lỗi khi cập nhật thuốc: ' . $e->getMessage());
        }  
}
    public function edit(HospitalRoom $hospital_room)
    {
        $departments = Department::all();
        $users = User::all();
        return view('hospital_rooms.edit', compact('hospital_room', 'departments', 'users'));
    }

    public function update(Request $request, HospitalRoom $hospital_room)
{
    try{
            $validated = $request->validate([
            'department_id' => 'required|exists:departments,id',
            'room_code' => 'required|string|max:20|unique:hospital_rooms,room_code,' . $hospital_room->id,
            'room_type' => 'required|string|max:100',
            'total_beds' => 'required|integer|min:1',
            'occupied_beds' => 'nullable|integer|min:0',
            'status' => 'nullable|in:available,in_use,cleaning,maintenance',
            'user_ids' => 'nullable|array',
            'user_ids.*' => 'exists:users,id',
        ]);
        $hospital_room->update($validated);
         // 🔹 Ghi log thành công
                AuditHelper::log('Cập nhật thông tin nhân viên', $hospital_room->name, 'Thành công');
        return redirect()->route('hospital_rooms.index')->with('success', 'Cập nhật thành công!');
    }catch(\Exception $e){
         // 🔹 Ghi log thất bại
                AuditHelper::log('Cập nhật thông tin nhân viên', $hospital_room->name ?? 'Không rõ', 'Thất bại');
            return redirect()->back()->with('error', 'Lỗi khi cập nhật thuốc: ' . $e->getMessage());
    }
}


    public function destroy(HospitalRoom $hospital_room)
    {
        $hospital_room->delete();
        return redirect()->route('hospital_rooms.index')->with('success', 'Xóa phòng bệnh thành công!');
    }
   
}
