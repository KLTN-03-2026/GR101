@extends('layouts.master_user')
@section('content')
    <div class="container py-4">

        <h3>🍳 Món ăn: {{ $tenmon }}</h3>
        <div class="row">
            <div class="col-6">
                <h5 class="mt-3">📘 Công thức</h5>
                <pre style="white-space: pre-wrap; background:#f7f7f7; padding:12px; border-radius:6px;">
{{ $congthuc }}
    </pre>
            </div>

            <div class="col-6">
                <h4 class="mt-4">🛒 Nguyên liệu cần mua</h4>
                <pre style="white-space: pre-wrap; background:#f7f7f7; padding:12px; border-radius:6px;">
            @foreach ($nguyenlieu as $nl)
{{ $nl }}
@endforeach
                </pre>
            </div>
        </div>

        <h4 class="mt-4">🏪 Sản phẩm gợi ý</h4>

        @if ($products->isEmpty())
            <p>Không tìm thấy sản phẩm phù hợp.</p>
        @else
            <div class="row">
                @foreach ($products as $product)
                    @php
                        // nếu item là model => dùng model, nếu array => convert sang object tạm
                        if (is_array($product)) {
                            $prod = (object) $product;
                        } else {
                            $prod = $product;
                        }
                    @endphp

                    <div class="col-3 mt-40">
                        <div class="">
                            <div class="product-image">
                                <a href="{{ is_array($product) ? $prod->url : route('web.detail', $prod->id) }}">
                                    <img src="{{ is_array($product) ? asset($prod->image) : $prod->getImage() }}"
                                        alt="{{ $prod->name }}">
                                </a>
                            </div>
                            <div class="product_desc">
                                <h4><a class="product_name"
                                        href="{{ is_array($product) ? $prod->url : route('web.detail', $prod->id) }}">{{ $prod->name }}</a>
                                </h4>
                                <div class="price-box">
                                    <span
                                        class="new-price">{{ is_array($product) ? number_format($prod->price) . 'đ' : formatVnd($prod->getPrice()) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

            </div>
        @endif

    </div>
@endsection
