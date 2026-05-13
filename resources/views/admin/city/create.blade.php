@extends('layouts.master_admin')

@section('content')
    <div class="pagetitle">
        <h1>Thêm phí vận chuyển tỉnh/thành phố</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item active">Thêm</li>
                <li class="breadcrumb-item"><a href="{{ route('admin.cities.index') }}">Danh sách</a></li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <form action="{{ route('admin.cities.store') }}" method="post">
        @csrf

        <div class="form-group pt-3">
            <label for="name">Tên tỉnh/thành phố @include('admin.include.required_icon')</label>
            <input type="text" id="name" name="name" class="form-control" value="{{ old('name') }}" placeholder="Ví dụ: Quảng Nam">
            @error('name')
            <p class="alert alert-danger mt-2">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-group pt-3">
            <label for="shipping_fee">Phí vận chuyển @include('admin.include.required_icon')</label>
            <input type="text" id="shipping_fee" name="shipping_fee" class="form-control" value="{{ old('shipping_fee') }}" placeholder="Ví dụ: 30000">
            @error('shipping_fee')
            <p class="alert alert-danger mt-2">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-group pt-3">
            <button type="submit" class="btn btn-primary">Lưu</button>
            <a href="{{ route('admin.cities.index') }}" class="btn btn-secondary">Quay lại</a>
        </div>
    </form>
@endsection
