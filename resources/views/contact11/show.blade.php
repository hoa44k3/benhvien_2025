@extends('admin.master')

@section('title','Chi tiết liên hệ')

@section('body')
<div class="p-6">
    <h1 class="text-xl font-bold mb-4">Chi tiết liên hệ #{{ $contact->id }}</h1>

    <p><strong>Họ tên:</strong> {{ $contact->name }}</p>
    <p><strong>Email:</strong> {{ $contact->email }}</p>
    <p><strong>Số điện thoại:</strong> {{ $contact->phone }}</p>
    <p><strong>Chủ đề:</strong> {{ $contact->subject }}</p>
    <p class="mt-3"><strong>Nội dung:</strong></p>
    <p class="bg-gray-100 p-3 rounded">{{ $contact->message }}</p>

    <a href="{{ route('admin.contacts.index') }}" class="mt-4 inline-block bg-gray-600 text-white px-4 py-2 rounded">
        Quay lại
    </a>
</div>
@endsection
