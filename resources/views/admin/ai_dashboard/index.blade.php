@extends('layouts.master_admin')
@section('content')
    <div class="pagetitle">
        <h1>🤖 AI Dashboard - Trí tuệ kinh doanh</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">AI Insights</li>
            </ol>
        </nav>
    </div>

    <section class="section dashboard">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body pt-3">
                        <!-- Bordered Tabs (product suggestions hidden; only PB21 tab) -->
                        <ul class="nav nav-tabs nav-tabs-bordered" id="borderedTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="business-tab" data-bs-toggle="tab" data-bs-target="#bordered-business" type="button" role="tab" aria-controls="business" aria-selected="true">📊 Phân tích Chiến lược (PB21)</button>
                            </li>
                        </ul>
                        <div class="tab-content pt-2" id="borderedTabContent">

                            <!-- TAB 2: PHÂN TÍCH CHIẾN LƯỢC (PB21) -->
                            <div class="tab-pane fade show active" id="bordered-business" role="tabpanel" aria-labelledby="business-tab">
                                <div class="mt-4 text-center" id="analysis-placeholder">
                                    <div class="py-5">
                                        <i class="bi bi-robot display-1 text-primary mb-4"></i>
                                        <h3>AI Business Intelligence</h3>
                                        <p class="text-muted">Nhấn nút bên dưới để AI phân tích dữ liệu bán hàng thực tế tuần qua.</p>
                                        @if(auth('admin')->user()->isSuperAdmin())
                                            <button id="btn-run-analysis" class="btn btn-primary btn-lg px-5 rounded-pill shadow">
                                                <i class="bi bi-stars"></i> Bắt đầu phân tích ngay
                                            </button>
                                        @else
                                            <div class="alert alert-warning">Bạn không có quyền chạy phân tích. Vui lòng yêu cầu Super Admin thực hiện.</div>
                                        @endif
                                    </div>
                                </div>

                                <div id="analysis-loading" class="d-none text-center py-5">
                                    <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                    <h5 class="mt-3">AI đang "đọc" dữ liệu kinh doanh...</h5>
                                    <p class="text-muted italic">Vui lòng đợi trong giây lát.</p>
                                </div>

                                <div id="analysis-result" class="d-none">
                                    <div class="row">
                                        <!-- Section 1: Nhận định -->
                                        <div class="col-md-4">
                                            <div class="card h-100 border-start border-primary border-4 shadow-sm">
                                                <div class="card-body pt-3">
                                                    <h5 class="card-title text-primary"><i class="bi bi-graph-up"></i> 1. Nhận định tình hình</h5>
                                                    <p id="result-nhandinh" class="card-text text-dark font-bold"></p>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Section 2: Cảnh báo -->
                                        <div class="col-md-4">
                                            <div class="card h-100 border-start border-danger border-4 shadow-sm">
                                                <div class="card-body pt-3">
                                                    <h5 class="card-title text-danger"><i class="bi bi-exclamation-triangle"></i> 2. Cảnh báo rủi ro</h5>
                                                    <p id="result-canhbao" class="card-text text-dark font-bold"></p>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Section 3: Đề xuất -->
                                        <div class="col-md-4">
                                            <div class="card h-100 border-start border-success border-4 shadow-sm">
                                                <div class="card-body pt-3">
                                                    <h5 class="card-title text-success"><i class="bi bi-lightbulb"></i> 3. Đề xuất hành động</h5>
                                                    <p id="result-dexuat" class="card-text text-dark font-bold"></p>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-12 mt-4">
                                            <div class="alert alert-success d-flex align-items-center justify-content-between py-4 shadow-sm border-0">
                                                <div>
                                                    <h4 class="alert-heading mb-1"><i class="bi bi-check2-circle"></i> Tối ưu hóa ngay bây giờ</h4>
                                                    <p class="mb-0">Dựa trên đề xuất của AI, bạn nên tạo mã giảm giá để thúc đẩy doanh thu.</p>
                                                </div>
                                                <a href="{{ route('admin.coupons.create') }}" id="btn-apply-suggestion" class="btn btn-success btn-lg px-4 shadow">
                                                    Áp dụng đề xuất <i class="bi bi-arrow-right"></i>
                                                </a>
                                            </div>
                                        </div>
                                        <!-- Suggestions will be injected here after analysis -->
                                        <div class="col-12 mt-4">
                                            <div id="suggestions-container"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div><!-- End Bordered Tabs -->
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('js')
    <script>
        $(document).ready(function () {
            // Auto-fetch suggestions on page load so admin sees existing suggestions immediately
            function fetchSuggestionsAndRender() {
                $.ajax({
                    method: 'GET',
                    url: '{{ route("admin.ai.suggestions") }}',
                    success: function(res) {
                        if (res.success && res.data && res.data.length > 0) {
                            let html = '<div class="card"><div class="card-header bg-secondary text-white"><h5 class="mb-0"><i class="bi bi-lightbulb"></i> Gợi ý sản phẩm</h5></div><div class="card-body"><div class="row">';
                            res.data.forEach(function(s){
                                html += '<div class="col-md-6 mb-3">';
                                html += '<div class="card border-light mb-0 shadow-sm"><div class="card-body">';
                                html += '<h6 class="card-title mb-2">' + (s.title || '') + '</h6>';
                                html += '<p class="text-muted small">' + (s.description || '') + '</p>';
                                html += '<div class="alert alert-light py-2 px-3 border"><strong>💡 AI gợi ý:</strong> ' + (s.action || '') + '</div>';
                                if (s.product) {
                                    html += '<a href="' + s.product.edit_url + '" class="btn btn-sm btn-primary"><i class="bi bi-pencil"></i> Chỉnh sửa</a>';
                                }
                                html += '</div></div></div>';
                            });
                            html += '</div></div></div>';
                            $('#suggestions-container').html(html);
                        } else {
                            $('#suggestions-container').html('');
                        }
                    },
                    error: function() {
                        $('#suggestions-container').html('');
                    }
                });
            }

            // call once on load
            fetchSuggestionsAndRender();
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
                        card.fadeOut(300, function () { $(this).remove(); });
                    }
                });
            });

            // Run Business Analysis (PB21)
            $('#btn-run-analysis').click(function() {
                $('#analysis-placeholder').addClass('d-none');
                $('#analysis-loading').removeClass('d-none');
                $('#analysis-result').addClass('d-none');

                $.ajax({
                    method: 'GET',
                    url: '{{ route("admin.ai.analytics") }}',
                    success: function(response) {
                        if(response.success) {
                            const data = response.data;
                            $('#result-nhandinh').text(data.nhan_dinh);
                            $('#result-canhbao').text(data.canh_bao);
                            $('#result-dexuat').text(data.de_xuat);

                            // Pre-fill Coupon Link if action_params exist
                            if(data.action_params) {
                                // Validate AI suggested coupon params before pre-filling
                                let paramsObj = data.action_params;
                                if (paramsObj.type === 'percent') {
                                    const val = Number(paramsObj.discount_value);
                                    if (!isNaN(val) && val > 100) {
                                        paramsObj.discount_value = 100; // cap to 100%
                                        $('#analysis-result').prepend('<div class="alert alert-warning" id="ai-warning">AI đề xuất phần trăm lớn hơn 100% — đã điều chỉnh xuống 100% để an toàn.</div>');
                                    }
                                }

                                const params = new URLSearchParams(paramsObj).toString();
                                $('#btn-apply-suggestion').attr('href', '{{ route("admin.coupons.create") }}?' + params);
                            }

                            $('#analysis-loading').addClass('d-none');
                            $('#analysis-result').removeClass('d-none');

                            // After analysis, fetch product suggestions and render them
                            $.ajax({
                                method: 'GET',
                                url: '{{ route("admin.ai.suggestions") }}',
                                success: function(res) {
                                    if (res.success && res.data && res.data.length > 0) {
                                        let html = '<div class="card"><div class="card-header bg-secondary text-white"><h5 class="mb-0"><i class="bi bi-lightbulb"></i> Gợi ý sản phẩm</h5></div><div class="card-body"><div class="row">';
                                        res.data.forEach(function(s){
                                            html += '<div class="col-md-6 mb-3">';
                                            html += '<div class="card border-light mb-0 shadow-sm"><div class="card-body">';
                                            html += '<h6 class="card-title mb-2">' + (s.title || '') + '</h6>';
                                            html += '<p class="text-muted small">' + (s.description || '') + '</p>';
                                            html += '<div class="alert alert-light py-2 px-3 border"><strong>💡 AI gợi ý:</strong> ' + (s.action || '') + '</div>';
                                            if (s.product) {
                                                html += '<a href="' + s.product.edit_url + '" class="btn btn-sm btn-primary"><i class="bi bi-pencil"></i> Chỉnh sửa</a>';
                                            }
                                            html += '</div></div></div>';
                                        });
                                        html += '</div></div></div>';
                                        $('#suggestions-container').html(html);
                                    } else {
                                        $('#suggestions-container').html('');
                                    }
                                },
                                error: function() {
                                    // ignore suggestions fetch error silently
                                }
                            });
                        }
                    },
                    error: function(xhr) {
                        let msg = 'Có lỗi xảy ra khi kết nối tới AI.';
                        try {
                            if (xhr && xhr.responseJSON && xhr.responseJSON.message) {
                                msg = xhr.responseJSON.message;
                            } else if (xhr && xhr.statusText) {
                                msg = xhr.statusText + ' (' + xhr.status + ')';
                            }
                        } catch (e) {
                            // ignore
                        }
                        alert(msg);
                        $('#analysis-placeholder').removeClass('d-none');
                        $('#analysis-loading').addClass('d-none');
                    }
                });
            });

            // Debug: simulate AI response and suggestions (only available when app.debug=true)
            $('#btn-debug-simulate').click(function() {
                const sample = {
                    nhan_dinh: 'Doanh thu tuần này tăng 12% so với tuần trước, chủ yếu do nhóm rau củ.',
                    canh_bao: 'Một vài mặt hàng trái cây có tồn kho lớn và tốc độ bán chậm.',
                    de_xuat: 'Tạo mã giảm 10% cho Cà chua; Tăng tồn kho rau muống.',
                    action_params: {
                        coupon_code: 'AI_TEST',
                        discount_value: 10,
                        type: 'percent'
                    }
                };

                // show result
                $('#result-nhandinh').text(sample.nhan_dinh);
                $('#result-canhbao').text(sample.canh_bao);
                $('#result-dexuat').text(sample.de_xuat);
                $('#analysis-placeholder').addClass('d-none');
                $('#analysis-loading').addClass('d-none');
                $('#analysis-result').removeClass('d-none');

                // simulate suggestions
                const suggestions = [
                    {title: 'Giảm giá Cà chua', description: 'Cà chua bán chậm', action: 'Tạo coupon 10%', product: {edit_url: '#'}},
                    {title: 'Bundle Rau Xanh', description: 'Bundle rau để tăng doanh số', action: 'Tạo bundle 3 món', product: {edit_url: '#'}}
                ];

                let html = '<div class="card"><div class="card-header bg-secondary text-white"><h5 class="mb-0"><i class="bi bi-lightbulb"></i> Gợi ý sản phẩm</h5></div><div class="card-body"><div class="row">';
                suggestions.forEach(function(s){
                    html += '<div class="col-md-6 mb-3">';
                    html += '<div class="card border-light mb-0 shadow-sm"><div class="card-body">';
                    html += '<h6 class="card-title mb-2">' + s.title + '</h6>';
                    html += '<p class="text-muted small">' + s.description + '</p>';
                    html += '<div class="alert alert-light py-2 px-3 border"><strong>💡 AI gợi ý:</strong> ' + s.action + '</div>';
                    if (s.product) html += '<a href="' + s.product.edit_url + '" class="btn btn-sm btn-primary"><i class="bi bi-pencil"></i> Chỉnh sửa</a>';
                    html += '</div></div></div>';
                });
                html += '</div></div></div>';
                $('#suggestions-container').html(html);
            });
        });
    </script>
@endsection
