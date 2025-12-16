<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Comment;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
class PostController extends Controller
{
     public function index()
    {
        $posts = Post::with('user')->latest()->paginate(10);
        return view('posts.index', compact('posts'));
    }

    public function create()
    {
        return view('posts.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255',
            'description' => 'nullable',
            'content' => 'required',
            'image' => 'nullable|image|max:2048',
            'status' => 'required|in:published,draft'
        ]);

        $data = $request->only([
            'title', 'description', 'content', 'status'
        ]);

        $data['slug'] = Str::slug($request->title);
        $data['user_id'] = Auth::id();
        $data['is_featured'] = $request->has('is_featured');

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('posts', 'public');
        }

        Post::create($data);

        return redirect()->route('posts.index')
            ->with('success', 'Thêm bài viết thành công');
    }

    public function show(Post $post)
    {
        return view('posts.show', compact('post'));
    }

    public function edit(Post $post)
    {
        return view('posts.edit', compact('post'));
    }

    public function update(Request $request, Post $post)
    {
        $request->validate([
            'title' => 'required|max:255',
            'description' => 'nullable',
            'content' => 'required',
            'image' => 'nullable|image|max:2048',
            'status' => 'required|in:published,draft'
        ]);

        $data = $request->only([
            'title', 'description', 'content', 'status'
        ]);

        $data['slug'] = Str::slug($request->title);
        $data['is_featured'] = $request->has('is_featured');

        if ($request->hasFile('image')) {
            if ($post->image) {
                Storage::disk('public')->delete($post->image);
            }
            $data['image'] = $request->file('image')->store('posts', 'public');
        }

        $post->update($data);

        return redirect()->route('posts.index')
            ->with('success', 'Cập nhật bài viết thành công');
    }

    public function destroy(Post $post)
    {
        if ($post->image) {
            Storage::disk('public')->delete($post->image);
        }

        $post->delete();
        return back()->with('success', 'Đã xóa bài viết');
    }
}
