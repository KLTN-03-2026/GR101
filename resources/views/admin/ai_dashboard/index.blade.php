@extends('layouts.master_admin')
@section('content')
    <div class="pagetitle">
        <h1>🤖 AI Dashboard - Gợi ý Tối ưu Doanh thu</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">AI Insights</li>
            </ol>
        </nav>
    </div>

    <section class="section dashboard">
        <div class="row">
            <!-- Summary Cards -->
            <div class="col-lg-12">
                <div class="alert alert-info">
                    <i class="bi bi-info-circle"></i>
                    <strong>Hệ thống AI</strong> đã phân tích {{ $groupedSuggestions->flatten()->count() }} gợi ý dựa trên
                    hành vi khách hàng và xu hướng thị trường.
                </div>
            </div>

            <!-- Pricing Suggestions -->
            @if(isset($groupedSuggestions['pricing']) && $groupedSuggestions['pricing']->count() > 0)
                <div class="col-lg-12 mb-4">
                    <div class="card">
                        <div class="card-header bg-warning text-white">
                            <h5 class="mb-0"><i class="bi bi-tag"></i> Gợi ý về Giá bán
                                ({{ $groupedSuggestions['pricing']->count() }})</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                @foreach($groupedSuggestions['pricing'] as $suggestion)
                                    <div class="col-md-6 mb-3">
                                        <div class="card border-warning">
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between align-items-start">
                                                    <div>
                                                        <h6 class="card-title">{{ $suggestion->title }}</h6>
                                                        <p class="card-text text-muted small">{{ $suggestion->description }}</p>
                                                        <div class="alert alert-light py-2 px-3">
                                                            <strong>💡 AI gợi ý Hành động:</strong> {{ $suggestion->action_recommendation }}
                                                        </div>
                                                        @if($suggestion->product)
                                                            <a href="{{ route('admin.products.edit', $suggestion->product_id) }}"
                                                                class="btn btn-sm btn-primary">
                                                                <i class="bi bi-pencil"></i> Chỉnh sửa sản phẩm
                                                            </a>
                                                        @endif
                                                    </div>
                                                    <button class="btn btn-sm btn-outline-secondary dismiss-btn"
                                                        data-id="{{ $suggestion->id }}">
                                                        <i class="bi bi-x"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Inventory Suggestions -->
            @if(isset($groupedSuggestions['inventory']) && $groupedSuggestions['inventory']->count() > 0)
                <div class="col-lg-12 mb-4">
                    <div class="card">
                        <div class="card-header bg-danger text-white">
                            <h5 class="mb-0"><i class="bi bi-box-seam"></i> Gợi ý Tồn kho
                                ({{ $groupedSuggestions['inventory']->count() }})</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                @foreach($groupedSuggestions['inventory'] as $suggestion)
                                    <div class="col-md-6 mb-3">
                                        <div class="card border-danger">
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between align-items-start">
                                                    <div>
                                                        <h6 class="card-title">{{ $suggestion->title }}</h6>
                                                        <p class="card-text text-muted small">{{ $suggestion->description }}</p>
                                                        <div class="alert alert-light py-2 px-3">
                                                            <strong>💡 AI gợi ý Hành động:</strong> {{ $suggestion->action_recommendation }}
                                                        </div>
                                                        @if($suggestion->product)
                                                            <a href="{{ route('admin.products.edit', $suggestion->product_id) }}"
                                                                class="btn btn-sm btn-danger">
                                                                <i class="bi bi-lightning"></i> Tạo Flash Sale
                                                            </a>
                                                        @endif
                                                    </div>
                                                    <button class="btn btn-sm btn-outline-secondary dismiss-btn"
                                                        data-id="{{ $suggestion->id }}">
                                                        <i class="bi bi-x"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Trending Products -->
            @if(isset($groupedSuggestions['trending']) && $groupedSuggestions['trending']->count() > 0)
                <div class="col-lg-12 mb-4">
                    <div class="card">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0"><i class="bi bi-graph-up-arrow"></i> Sản phẩm đang HOT
                                ({{ $groupedSuggestions['trending']->count() }})</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                @foreach($groupedSuggestions['trending'] as $suggestion)
                                    <div class="col-md-6 mb-3">
                                        <div class="card border-success">
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between align-items-start">
                                                    <div>
                                                        <h6 class="card-title">{{ $suggestion->title }}</h6>
                                                        <p class="card-text text-muted small">{{ $suggestion->description }}</p>
                                                        <div class="alert alert-light py-2 px-3">
                                                            <strong>💡 AI gợi ý Hành động:</strong> {{ $suggestion->action_recommendation }}
                                                        </div>
                                                        @if($suggestion->product)
                                                            <a href="{{ route('admin.products.edit', $suggestion->product_id) }}"
                                                                class="btn btn-sm btn-success">
                                                                <i class="bi bi-star"></i> Đẩy lên trang chủ
                                                            </a>
                                                        @endif
                                                    </div>
                                                    <button class="btn btn-sm btn-outline-secondary dismiss-btn"
                                                        data-id="{{ $suggestion->id }}">
                                                        <i class="bi bi-x"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Combo Suggestions -->
            @if(isset($groupedSuggestions['combo']) && $groupedSuggestions['combo']->count() > 0)
                <div class="col-lg-12 mb-4">
                    <div class="card">
                        <div class="card-header bg-info text-white">
                            <h5 class="mb-0"><i class="bi bi-basket"></i> Gợi ý Combo
                                ({{ $groupedSuggestions['combo']->count() }})</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                @foreach($groupedSuggestions['combo'] as $suggestion)
                                    <div class="col-md-6 mb-3">
                                        <div class="card border-info">
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between align-items-start">
                                                    <div>
                                                        <h6 class="card-title">{{ $suggestion->title }}</h6>
                                                        <p class="card-text text-muted small">{{ $suggestion->description }}</p>
                                                        <div class="alert alert-light py-2 px-3">
                                                            <strong>💡 AI gợi ý Hành động:</strong> {{ $suggestion->action_recommendation }}
                                                        </div>
                                                     
                                                    </div>
                                                    <button class="btn btn-sm btn-outline-secondary dismiss-btn"
                                                        data-id="{{ $suggestion->id }}">
                                                        <i class="bi bi-x"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @if($groupedSuggestions->flatten()->count() == 0)
                <div class="col-lg-12">
                    <div class="alert alert-secondary text-center">
                        <i class="bi bi-info-circle"></i>
                        Chưa có gợi ý nào. Hãy chạy lệnh <code>php artisan ai:generate-suggestions</code> để tạo gợi ý AI.
                    </div>
                </div>
            @endif
        </div>
    </section>
@endsection

@section('js')
    <script>
        $(document).ready(function () {
            // Dismiss suggestion
            $('.dismiss-btn').click(function () {
                const suggestionId = $(this).data('id');
                const card = $(this).closest('.col-md-6');

                $.ajax({
                    method: 'POST',
                    url: '{{ route("admin.ai.dismiss") }}',
                    data: {
                        suggestion_id: suggestionId,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function (response) {
                        card.fadeOut(300, function () {
                            $(this).remove();
                        });
                    }
                });
            });
        });
    </script>
@endsection