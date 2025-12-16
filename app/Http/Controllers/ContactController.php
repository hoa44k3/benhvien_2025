<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Contact;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactReply;
use Illuminate\Support\Facades\Auth;


class ContactController extends Controller
{
   public function index()
    {
        $contacts = Contact::orderBy('status', 'asc')->latest()->paginate(10);
        
        return view('contacts.index', compact('contacts'));
    }

    public function show(Contact $contact)
    {
        return view('contacts.show', compact('contact'));
    }

    public function reply(Request $request, Contact $contact)
    {
        $request->validate(['reply_message' => 'required']);

        $contact->update([
            'reply_message' => $request->reply_message,
            'replied_at' => now(),
            'replied_by' => Auth::id(),
            'status' => 'replied'
        ]);

        // Gửi mail
        try {
            Mail::to($contact->email)->send(new ContactReply($contact));
        } catch (\Exception $e) {
            return back()->with('error', 'Lưu phản hồi thành công nhưng gửi mail thất bại: ' . $e->getMessage());
        }

        return redirect()->route('contacts.index')->with('success', 'Đã gửi phản hồi thành công!');
    }
    
    public function destroy(Contact $contact)
    {
        $contact->delete();
        return back()->with('success', 'Đã xóa tin nhắn.');
    }
}
