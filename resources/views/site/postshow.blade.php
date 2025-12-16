@extends('site.master')

@section('title', $post->title)

@section('body')
<div class="container mx-auto max-w-5xl px-4 py-12">
    
    {{-- PHẦN 1: NỘI DUNG BÀI VIẾT --}}
    <article class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 mb-12">
        <div class="mb-6 flex flex-wrap gap-4 text-sm text-gray-500">
            <span class="bg-blue-100 text-blue-600 text-xs font-bold px-3 py-1 rounded-full">Tin tức</span>
            <span><i class="far fa-calendar-alt mr-1"></i> {{ $post->created_at->format('d/m/Y') }}</span>
            <span><i class="far fa-eye mr-1"></i> {{ $post->views }} lượt xem</span>
        </div>
        
        <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 mb-6 leading-tight">{{ $post->title }}</h1>
        
        @if($post->image)
            <img src="{{ asset('storage/'.$post->image) }}" class="w-full h-auto rounded-xl mb-8 object-cover shadow-md max-h-[500px]">
        @endif

        {{-- Nội dung chính --}}
        <div class="prose max-w-none text-gray-700 leading-relaxed text-lg">
            {!! $post->content !!}
        </div>
    </article>

    {{-- PHẦN 2: BÀI VIẾT LIÊN QUAN --}}
    @if($relatedPosts->count() > 0)
    <div class="mb-12">
        <h3 class="text-xl font-bold mb-4 text-gray-800 border-l-4 border-blue-600 pl-3">Bài viết liên quan</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($relatedPosts as $rel)
            <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden group">
                <a href="{{ route('site.postshow', $rel->id) }}" class="block h-40 overflow-hidden">
                    <img src="{{ $rel->image ? asset('storage/'.$rel->image) : 'https://placehold.co/600x400' }}" 
                         class="w-full h-full object-cover transform group-hover:scale-110 transition duration-500">
                </a>
                <div class="p-4">
                    <h4 class="font-bold text-gray-800 line-clamp-2 group-hover:text-blue-600">
                        <a href="{{ route('site.postshow', $rel->id) }}">{{ $rel->title }}</a>
                    </h4>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- PHẦN 3: BÌNH LUẬN --}}
    <div class="bg-gray-50 rounded-2xl p-6 md:p-8 border border-gray-200" id="comments-section">
        <h3 class="text-2xl font-bold mb-6 text-gray-800 flex items-center">
            <i class="far fa-comments mr-2 text-blue-600"></i> Bình luận ({{ $post->comments->count() }})
        </h3>

        {{-- Thông báo thành công --}}
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        {{-- Thông báo lỗi --}}
        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- FORM GỬI BÌNH LUẬN GỐC (CẤP 0) --}}
        <form action="{{ route('site.posts.comment', $post->id) }}" method="POST" class="mb-10 bg-white p-6 rounded-xl shadow-sm">
            @csrf
            <h4 class="font-bold text-gray-700 mb-4">Viết bình luận của bạn</h4>
            
            @if(!Auth::check())
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <input type="text" name="name" placeholder="Họ tên (Bắt buộc)" required 
                           class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                    <input type="email" name="email" placeholder="Email (Không bắt buộc)" 
                           class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                </div>
            @else
                <div class="mb-3 flex items-center gap-2">
                    <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center text-white font-bold">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                    <span class="font-bold text-gray-700">{{ Auth::user()->name }}</span>
                </div>
            @endif

            <textarea name="content" rows="3" placeholder="Nội dung bình luận..." required 
                      class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none mb-4"></textarea>
            
            <button type="submit" class="px-8 py-3 bg-blue-600 text-white font-bold rounded-lg hover:bg-blue-700 transition shadow-lg">
                Gửi bình luận
            </button>
        </form>

        {{-- DANH SÁCH BÌNH LUẬN (HIỂN THỊ ĐỆ QUY 3 CẤP) --}}
        <div class="space-y-6">
            @foreach($post->comments as $comment)
                {{-- CẤP 1: BÌNH LUẬN GỐC --}}
                <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm relative group">
                    <div class="flex items-start gap-3">
                        {{-- Avatar Cấp 1 --}}
                        <div class="flex-shrink-0 w-10 h-10 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center font-bold text-lg">
                            {{ substr($comment->name, 0, 1) }}
                        </div>
                        <div class="flex-grow">
                            <div class="flex justify-between items-center mb-1">
                                <h5 class="font-bold text-gray-800">{{ $comment->name }}</h5>
                                <span class="text-xs text-gray-400">{{ $comment->created_at->diffForHumans() }}</span>
                            </div>
                            <p class="text-gray-700 mb-2">{{ $comment->content }}</p>
                            
                            {{-- Nút Trả lời Cấp 1 --}}
                            <button onclick="toggleReplyForm({{ $comment->id }})" class="text-sm text-blue-600 font-semibold hover:underline flex items-center gap-1">
                                <i class="fas fa-reply"></i> Trả lời
                            </button>

                            {{-- Form Reply Cấp 1 (Ẩn) --}}
                            <div id="reply-form-{{ $comment->id }}" class="hidden mt-3 pt-3 border-t border-gray-100">
                                <form action="{{ route('site.posts.comment', $post->id) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                                    
                                    @if(!Auth::check())
                                    <div class="grid grid-cols-2 gap-2 mb-2">
                                        <input type="text" name="name" placeholder="Tên *" required class="p-2 border rounded text-sm w-full outline-none focus:border-blue-500">
                                        <input type="email" name="email" placeholder="Email" class="p-2 border rounded text-sm w-full outline-none focus:border-blue-500">
                                    </div>
                                    @endif

                                    <div class="flex gap-2">
                                        <input type="text" name="content" placeholder="Nhập câu trả lời..." required class="flex-grow p-2 border rounded text-sm outline-none focus:border-blue-500">
                                        <button type="submit" class="px-4 py-2 bg-gray-800 text-white text-xs font-bold rounded hover:bg-black transition">Gửi</button>
                                    </div>
                                </form>
                            </div>

                            {{-- CẤP 2: CÁC CÂU TRẢ LỜI CỦA CẤP 1 --}}
                            @if($comment->replies->count() > 0)
                                <div class="mt-4 space-y-4 border-l-2 border-gray-200 pl-4 md:pl-6">
                                    @foreach($comment->replies as $reply)
                                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-100">
                                            <div class="flex items-start gap-3">
                                                {{-- Avatar Cấp 2 --}}
                                                <div class="w-8 h-8 bg-purple-100 text-purple-600 rounded-full flex items-center justify-center text-sm font-bold">
                                                    {{ substr($reply->name, 0, 1) }}
                                                </div>
                                                <div class="flex-grow">
                                                    <div class="flex justify-between items-center">
                                                        <span class="font-bold text-sm text-gray-800">
                                                            {{ $reply->name }}
                                                            @if($reply->name == 'Admin') <span class="bg-blue-600 text-white text-[10px] px-1 rounded ml-1">QTV</span> @endif
                                                        </span>
                                                        <span class="text-xs text-gray-400">{{ $reply->created_at->diffForHumans() }}</span>
                                                    </div>
                                                    <p class="text-sm text-gray-600 mt-1 mb-2">{{ $reply->content }}</p>

                                                    {{-- Nút Trả lời Cấp 2 --}}
                                                    <button onclick="toggleReplyForm({{ $reply->id }})" class="text-xs text-blue-500 hover:underline font-semibold">Trả lời</button>

                                                    {{-- Form Reply Cấp 2 --}}
                                                    <div id="reply-form-{{ $reply->id }}" class="hidden mt-2">
                                                        <form action="{{ route('site.posts.comment', $post->id) }}" method="POST">
                                                            @csrf
                                                            <input type="hidden" name="parent_id" value="{{ $reply->id }}">
                                                            
                                                            @if(!Auth::check())
                                                            <div class="grid grid-cols-2 gap-2 mb-2">
                                                                <input type="text" name="name" placeholder="Tên *" required class="p-2 border rounded text-sm w-full outline-none focus:border-blue-500">
                                                                <input type="email" name="email" placeholder="Email" class="p-2 border rounded text-sm w-full outline-none focus:border-blue-500">
                                                            </div>
                                                            @endif

                                                            <div class="flex gap-2">
                                                                <input type="text" name="content" placeholder="Nhập câu trả lời..." required class="flex-grow p-2 border rounded text-sm outline-none focus:border-blue-500">
                                                                <button type="submit" class="px-4 py-2 bg-gray-800 text-white text-xs font-bold rounded hover:bg-black transition">Gửi</button>
                                                            </div>
                                                        </form>
                                                    </div>

                                                    {{-- CẤP 3: CÁC CÂU TRẢ LỜI CỦA CẤP 2 --}}
                                                    @if($reply->replies->count() > 0)
                                                        <div class="mt-3 ml-2 pl-3 border-l-2 border-purple-200 space-y-3">
                                                            @foreach($reply->replies as $subReply)
                                                                <div class="bg-white p-3 rounded shadow-sm border border-gray-100">
                                                                    <div class="flex items-start gap-2">
                                                                        <div class="w-6 h-6 bg-green-100 text-green-600 rounded-full flex items-center justify-center text-xs font-bold">
                                                                            {{ substr($subReply->name, 0, 1) }}
                                                                        </div>
                                                                        <div>
                                                                            <span class="font-bold text-xs text-gray-800">
                                                                                {{ $subReply->name }}
                                                                                @if($subReply->name == 'Admin') <span class="bg-blue-600 text-white text-[9px] px-1 rounded">QTV</span> @endif
                                                                            </span>
                                                                            <p class="text-xs text-gray-600 mt-1">{{ $subReply->content }}</p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

<script>
    function toggleReplyForm(id) {
        const form = document.getElementById('reply-form-' + id);
        if (form.classList.contains('hidden')) {
            form.classList.remove('hidden');
        } else {
            form.classList.add('hidden');
        }
    }
</script>
@endsection