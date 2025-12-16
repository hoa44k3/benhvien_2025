<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function index()
    {
        // $comments = Comment::with('post')
        //     ->whereNull('parent_id')
        //     ->latest()
        //     ->paginate(10);
    $comments = Comment::with(['post', 'parent']) 
        ->latest()
        ->paginate(10);     
        return view('comments.index', compact('comments'));
    }

    public function show(Comment $comment)
    {
        // load reply nhiều cấp
        $comment->load([
            'post',
            'replies.replies'
        ]);

        return view('comments.show', compact('comment'));
    }

    // Admin reply (nhiều cấp)
    public function reply(Request $request, Comment $comment)
    {
        $request->validate([
            'content' => 'required'
        ]);

        Comment::create([
            'post_id'     => $comment->post_id,
            'parent_id'   => $comment->id,
            'name'        => 'Admin',
            'content'     => $request->content,
            'status'      => 'approved',
            'is_visible'  => true,
            'approved_by'=> Auth::id()
        ]);

        return back()->with('success', 'Đã trả lời bình luận');
    }

    // Duyệt
    public function approve(Comment $comment)
    {
        $comment->update([
            'status' => 'approved',
            'is_visible' => true,
            'approved_by' => Auth::id()
        ]);

        return back();
    }

    // Ẩn / hiện
    public function toggleVisibility(Comment $comment)
    {
        $comment->update([
            'is_visible' => !$comment->is_visible
        ]);

        return back();
    }

    // AJAX delete
    public function destroy(Comment $comment)
    {
        $comment->delete();
        return response()->json(['success' => true]);
    }
}
