@extends('layouts.master_admin')

@section('content')
    <div class="pagetitle">
        <h1>Chi tiết liên hệ</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.contacts.index') }}">Danh sách</a></li>
                <li class="breadcrumb-item active">Chi tiết</li>
                <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">Tổng quan</a></li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <div class="card">
        <div class="card-body pt-3">
            <div class="row mb-3">
                <div class="col-lg-3 col-md-4 label" style="font-weight: bold; color: #4154f1;">Họ tên khách hàng:</div>
                <div class="col-lg-9 col-md-8">{{ $contact->name }}</div>
            </div>

            <div class="row mb-3">
                <div class="col-lg-3 col-md-4 label" style="font-weight: bold; color: #4154f1;">Email:</div>
                <div class="col-lg-9 col-md-8">{{ $contact->email }}</div>
            </div>

            <div class="row mb-3">
                <div class="col-lg-3 col-md-4 label" style="font-weight: bold; color: #4154f1;">Tiêu đề:</div>
                <div class="col-lg-9 col-md-8">{{ $contact->subject }}</div>
            </div>

            <div class="row mb-3">
                <div class="col-lg-3 col-md-4 label" style="font-weight: bold; color: #4154f1;">Thời gian gửi:</div>
                <div class="col-lg-9 col-md-8">{{ $contact->created_at->format('d/m/Y H:i:s') }}</div>
            </div>

            <div class="row mb-3">
                <div class="col-lg-3 col-md-4 label" style="font-weight: bold; color: #4154f1;">Nội dung tin nhắn:</div>
                <div class="col-lg-9 col-md-8 p-3 bg-light border rounded">
                    {!! nl2br(e($contact->content)) !!}
                </div>
            </div>

            <div class="text-center mt-4">
                <a href="{{ route('admin.contacts.index') }}" class="btn btn-secondary">Quay lại danh sách</a>
                <a href="mailto:{{ $contact->email }}?subject=Re: {{ $contact->subject }}" class="btn btn-primary">Gửi email phản hồi</a>
            </div>
        </div>
    </div>
@endsection
