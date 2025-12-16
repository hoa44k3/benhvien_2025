<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Faq;
class FaqController extends Controller
{
   public function index() {
        $faqs = Faq::orderBy('order')->get();
        return view('faqs.index', compact('faqs'));
    }
public function create() {
        return view('faqs.create');
    }
   // Xử lý lưu dữ liệu mới
    public function store(Request $request) {
        $request->validate(['question' => 'required', 'answer' => 'required']);
        
        // Xử lý checkbox: nếu không tick thì trả về 0
        $data = $request->all();
        $data['is_active'] = $request->has('is_active') ? 1 : 0;
        
        Faq::create($data);
        
        // Redirect về trang index thay vì back để trải nghiệm tốt hơn
        return redirect()->route('faqs.index')->with('success', 'Thêm câu hỏi thành công');
    }
    public function edit(Faq $faq) {
        return view('faqs.edit', compact('faq'));
    }

   public function update(Request $request, Faq $faq) {
        $request->validate(['question' => 'required', 'answer' => 'required']);
        
        $data = $request->all();
        // Checkbox HTML: Nếu bỏ tick nó sẽ không gửi lên request, cần set default 0
        $data['is_active'] = $request->has('is_active') ? 1 : 0;

        $faq->update($data);
        
        return redirect()->route('faqs.index')->with('success', 'Cập nhật thành công');
    }

    public function destroy(Faq $faq) {
        $faq->delete();
        return back()->with('success', 'Xóa thành công');
    }
}
