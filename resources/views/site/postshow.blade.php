@extends('site.master')

@section('title', $post->title)

@section('body')
<div class="bg-white min-h-screen pb-20">
    
    {{-- ARTICLE HEADER --}}
    <div class="bg-slate-900 text-white py-20 relative overflow-hidden">
        <div class="absolute inset-0 bg-primary/20 backdrop-blur-3xl z-0"></div>
        <div class="container mx-auto max-w-4xl px-4 relative z-10 text-center">
            <div class="inline-block px-3 py-1 rounded-full bg-white/10 border border-white/20 text-xs font-bold uppercase tracking-wider mb-4 text-sky-200">
                Tin tức y tế
            </div>
            <h1 class="text-3xl md:text-5xl font-extrabold mb-6 leading-tight">{{ $post->title }}</h1>
            <div class="flex items-center justify-center gap-6 text-sm text-slate-300">
                <span class="flex items-center"><i class="far fa-calendar-alt mr-2"></i> {{ $post->created_at->format('d/m/Y') }}</span>
                <span class="flex items-center"><i class="far fa-eye mr-2"></i> {{ $post->views }} lượt xem</span>
                <span class="flex items-center"><i class="far fa-user mr-2"></i> Admin</span>
            </div>
        </div>
    </div>

    <div class="container mx-auto max-w-4xl px-4 -mt-10 relative z-20">
        
        {{-- CONTENT CARD --}}
        <div class="bg-white rounded-2xl shadow-xl border border-slate-100 p-8 md:p-12 mb-12">
            @if($post->image)
                <img src="{{ asset('storage/'.$post->image) }}" class="w-full h-auto rounded-xl mb-10 shadow-md object-cover">
            @endif

            <div class="prose prose-lg prose-slate max-w-none prose-headings:font-bold prose-headings:text-slate-800 prose-p:text-slate-600 prose-a:text-primary hover:prose-a:text-sky-600 prose-img:rounded-xl">
                {!! $post->content !!}
            </div>
        </div>

        {{-- RELATED POSTS --}}
        @if($relatedPosts->count() > 0)
        <div class="mb-16">
            <h3 class="text-2xl font-bold text-slate-800 mb-6 flex items-center gap-3">
                <span class="w-1 h-8 bg-primary rounded-full"></span> Bài viết liên quan
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($relatedPosts as $rel)
                <a href="{{ route('site.postshow', $rel->id) }}" class="group block bg-white border border-slate-200 rounded-xl overflow-hidden hover:shadow-lg transition duration-300">
                    <div class="h-48 overflow-hidden">
                        <img src="{{ $rel->image ? asset('storage/'.$rel->image) : 'https://placehold.co/600x400' }}" 
                             class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                    </div>
                    <div class="p-5">
                        <h4 class="font-bold text-slate-800 line-clamp-2 group-hover:text-primary transition mb-2">
                            {{ $rel->title }}
                        </h4>
                        <span class="text-xs text-slate-400">{{ $rel->created_at->format('d/m/Y') }}</span>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
        @endif

        {{-- COMMENTS SECTION --}}
        <div class="bg-slate-50 rounded-3xl p-8 md:p-10 border border-slate-200" id="comments-section">
            <div class="flex items-center justify-between mb-8">
                <h3 class="text-2xl font-bold text-slate-800">Bình luận ({{ $post->comments->count() }})</h3>
            </div>

            @if(session('success'))
                <div class="mb-6 px-4 py-3 bg-green-100 text-green-700 rounded-xl text-sm font-medium">
                    {{ session('success') }}
                </div>
            @endif

            {{-- COMMENT FORM --}}
            <form action="{{ route('site.posts.comment', $post->id) }}" method="POST" class="mb-12">
                @csrf
                <div class="flex gap-4">
                    <div class="flex-shrink-0">
                        @auth
                             <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=0ea5e9&color=fff" class="w-10 h-10 rounded-full">
                        @else
                            <div class="w-10 h-10 bg-slate-200 rounded-full flex items-center justify-center text-slate-400"><i class="fas fa-user"></i></div>
                        @endauth
                    </div>
                    <div class="flex-grow">
                        @if(!Auth::check())
                            <div class="grid grid-cols-2 gap-4 mb-4">
                                <input type="text" name="name" placeholder="Họ tên *" required class="w-full px-4 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:border-primary">
                                <input type="email" name="email" placeholder="Email" class="w-full px-4 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:border-primary">
                            </div>
                        @endif
                        <textarea name="content" rows="3" placeholder="Chia sẻ ý kiến của bạn..." required 
                                  class="w-full px-4 py-3 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition resize-none"></textarea>
                        <div class="mt-3 text-right">
                            <button type="submit" class="px-6 py-2 bg-slate-800 text-white text-sm font-bold rounded-lg hover:bg-slate-900 transition">Gửi bình luận</button>
                        </div>
                    </div>
                </div>
            </form>

            {{-- COMMENTS LIST --}}
            <div class="space-y-8">
                @foreach($post->comments as $comment)
                    <div class="relative">
                        {{-- Parent Comment --}}
                        <div class="flex gap-4">
                            <div class="flex-shrink-0 w-10 h-10 bg-white border border-slate-200 rounded-full flex items-center justify-center font-bold text-slate-600 shadow-sm">
                                {{ substr($comment->name, 0, 1) }}
                            </div>
                            <div class="flex-grow">
                                <div class="bg-white p-4 rounded-2xl rounded-tl-none border border-slate-200 shadow-sm inline-block min-w-[200px]">
                                    <div class="flex items-center justify-between mb-1 gap-4">
                                        <h5 class="font-bold text-slate-800 text-sm">{{ $comment->name }}</h5>
                                        <span class="text-xs text-slate-400">{{ $comment->created_at->diffForHumans() }}</span>
                                    </div>
                                    <p class="text-slate-600 text-sm">{{ $comment->content }}</p>
                                </div>
                                <div class="mt-1 ml-2">
                                    <button onclick="toggleReplyForm({{ $comment->id }})" class="text-xs font-bold text-slate-500 hover:text-primary transition">Trả lời</button>
                                </div>

                                {{-- Reply Form Level 1 --}}
                                <div id="reply-form-{{ $comment->id }}" class="hidden mt-3">
                                    <form action="{{ route('site.posts.comment', $post->id) }}" method="POST" class="flex gap-3 items-start">
                                        @csrf
                                        <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                                        @if(!Auth::check())
                                            <input type="text" name="name" placeholder="Tên*" required class="w-32 px-3 py-2 border rounded-lg text-xs">
                                        @endif
                                        <input type="text" name="content" placeholder="Viết câu trả lời..." required class="flex-grow px-3 py-2 border rounded-lg text-xs focus:border-primary outline-none">
                                        <button type="submit" class="px-3 py-2 bg-primary text-white text-xs font-bold rounded-lg hover:bg-sky-600">Gửi</button>
                                    </form>
                                </div>

                                {{-- Replies Level 2 --}}
                                @if($comment->replies->count() > 0)
                                    <div class="mt-4 space-y-4 pl-4 border-l-2 border-slate-200 ml-2">
                                        @foreach($comment->replies as $reply)
                                            <div class="flex gap-3">
                                                <div class="w-8 h-8 bg-purple-50 text-purple-600 rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0 border border-purple-100">
                                                    {{ substr($reply->name, 0, 1) }}
                                                </div>
                                                <div class="flex-grow">
                                                    <div class="bg-slate-100 p-3 rounded-xl rounded-tl-none inline-block">
                                                        <span class="font-bold text-xs text-slate-800">
                                                            {{ $reply->name }}
                                                            @if($reply->name == 'Admin') <span class="bg-primary text-white text-[9px] px-1 rounded ml-1">QTV</span> @endif
                                                        </span>
                                                        <p class="text-xs text-slate-600 mt-0.5">{{ $reply->content }}</p>
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
</div>

<script>
    function toggleReplyForm(id) {
        const form = document.getElementById('reply-form-' + id);
        form.classList.toggle('hidden');
    }
</script>
@endsection