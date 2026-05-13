<?php
return [
    [
        'title' => 'Tổng quan',
        'route' => 'admin.index',
        'icon' => '<i class="bi bi-grid"></i>'
    ],
    [
        'title' => 'AI Dashboard',
        'route' => 'admin.ai.dashboard',
        'icon' => '<i class="bi bi-robot"></i>'
    ],
    [
        'title' => 'Quản lý liên hệ',
        'icon' => '<i class="bi bi-envelope"></i>',
        'route' => 'admin.contacts.index'
    ],
    [
        'title' => 'Quản lý sản phẩm',
        'icon' => '<i class="bi bi-box-seam"></i>',
        'route' => 'admin.products.index'
    ],
    [
        'title' => 'Quản lý loại sản phẩm',
        'icon' => '<i class="bi bi-tags"></i>',
        'route' => 'admin.categories.index'
    ],
    // Banner management removed
    [
        'title' => 'Quản lý đơn đặt hàng',
        'icon' => '<i class="bi bi-cart-check"></i>',
        'route' => 'admin.orders.index'
    ],
    [
        'title' => 'Quản lý khách hàng',
        'icon' => '<i class="bi bi-people"></i>',
        'route' => 'admin.users.index'
    ],
    [
        'title' => 'Quản lý đánh giá',
        'icon' => '<i class="bi bi-star"></i>',
        'route' => 'admin.reviews.index'
    ],
    [
        'title' => 'Quản lý mã khuyến mãi',
        'icon' => '<i class="bi bi-ticket-perforated"></i>',
        'route' => 'admin.coupons.index'
    ],
    [
        'title' => 'Quản lý phí vận chuyển',
        'icon' => '<i class="bi bi-truck"></i>',
        'route' => 'admin.cities.index'
    ],
    [
        'title' => 'Quản lý quản trị viên',
        'icon' => '<i class="bi bi-person-badge"></i>',
        'route' => 'admin.admins.index'
    ],
];
