@extends('layouts.master_admin')

@section('search')
    <div class="search-bar">
        <form class="search-form d-flex align-items-center" method="GET" action="{{ route('admin.products.index') }}">
            <input type="text" name="search" placeholder="Tìm kiếm" value="{{ request()->get('search') ?? '' }}">
            <button type="submit" title="Search"><i class="bi bi-search"></i></button>
        </form>
    </div><!-- End Search Bar -->
@endsection

@section('content')
    <div class="pagetitle d-flex justify-content-between align-items-center">
        <div>
            <h1>Danh sách sản phẩm</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">Tổng quan</a></li>
                    <li class="breadcrumb-item active">Sản phẩm</li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-1"></i> Thêm sản phẩm mới
        </a>
    </div><!-- End Page Title -->

    <div class="card shadow-sm border-0">
        <div class="card-body pt-3">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center" width="50">ID</th>
                            <th width="100">Ảnh</th>
                            <th>Tên sản phẩm</th>
                            <th class="text-center">Số lượng</th>
                            <th>Chuyên mục</th>
                            <th width="250">Mô tả</th>
                            <th class="text-center">Trạng thái</th>
                            <th class="text-center" width="100">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($listProduct as $product)
                            <tr>
                                <td class="text-center font-weight-bold">{{ $product->id }}</td>
                                <td>
                                    <div class="product-img-container shadow-sm border rounded p-1 bg-white" style="width: 80px; height: 80px; display: flex; align-items: center; justify-content: center; overflow: hidden;">
                                        <img src="{{ $product->getImage() }}" 
                                             alt="{{ $product->name }}" 
                                             style="max-width: 100%; max-height: 100%; object-fit: contain;">
                                    </div>
                                </td>
                                <td class="fw-bold">{{ $product->name }}</td>
                                <td class="text-center">
                                    @php
                                        $qty = $product->getQuantityActive();
                                    @endphp
                                    <span class="badge {{ $qty <= 10 ? 'bg-danger' : 'bg-success' }}" style="font-size: 14px;">
                                        {{ $qty }} {{ $product->unit ? '/ ' . $product->unit : '' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-info text-dark bg-opacity-10 border border-info border-opacity-25">
                                        {{ $product->Category->name ?? 'N/A' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="text-truncate" style="max-width: 250px;" title="{{ $product->description }}">
                                        {{ $product->description ?: 'Không có mô tả' }}
                                    </div>
                                </td>
                                <td class="text-center">
                                    {!! mapStatusProduct($product->status) !!}
                                </td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-1">
                                        <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-outline-success btn-sm" title="Sửa">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('admin.products.destroy', $product->id ) }}" method="post" class="d-inline">
                                            @csrf
                                            @method('delete')
                                            <button type="submit" class="btn btn-outline-danger btn-sm btn-delete-index" data-id="{{ $product->id }}" title="Xóa">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $listProduct->appends(request()->input())->links() }}
            </div>
        </div>
    </div>

    <style>
        .table thead th {
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 700;
            color: #4154f1;
            border-bottom: 2px solid #ebeef4;
        }
        .product-img-container img {
            transition: transform 0.3s ease;
        }
        .product-img-container:hover img {
            transform: scale(1.1);
        }
    </style>
@endsection
