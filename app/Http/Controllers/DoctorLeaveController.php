<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\DoctorLeave;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// gửi đơn
class DoctorLeaveController extends Controller
{// Danh sách đơn nghỉ của tôi
    public function index() {
        $leaves = DoctorLeave::where('user_id', Auth::id())->latest()->paginate(10);
        return view('doctor.leaves.index', compact('leaves'));
    }

    // Form tạo đơn
    public function create() {
        return view('doctor.leaves.create');
    }

    // Lưu đơn
    public function store(Request $request) {
        $request->validate([
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string|max:255',
        ]);

        DoctorLeave::create([
            'user_id' => Auth::id(),
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'reason' => $request->reason,
            'status' => 'pending'
        ]);

        return redirect()->route('doctor.leaves.index')->with('success', 'Đã gửi đơn xin nghỉ, vui lòng chờ duyệt.');
    }
}
