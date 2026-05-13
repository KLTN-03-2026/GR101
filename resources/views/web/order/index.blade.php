@extends('layouts.master_user')

@section('content')
<div class="bg-gray-50 min-h-screen font-sans pb-20">
    <div class="breadcrumb-area py-10">
        <div class="container">
            <nav class="flex text-xs font-bold uppercase tracking-widest text-gray-400 gap-4">
                <a href="{{ route('web.index') }}" class="hover:text-green-900 transition-colors">Trang chủ</a>
                <i class="fa fa-chevron-right text-[8px] mt-0.5"></i>
                <span class="text-gray-900">Đơn hàng của tôi</span>
            </nav>
        </div>
    </div>

    <div class="container">
        <div class="max-w-5xl mx-auto">
            <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-8 border-b border-gray-50 flex items-center justify-between">
                    <h2 class="text-2xl font-black text-gray-900 tracking-tight">Lịch sử đơn hàng</h2>
                    <span class="bg-soft-green text-green-700 text-[10px] font-black px-4 py-1.5 rounded-full uppercase tracking-widest">
                        {{ $listOrder->total() }} đơn hàng
                    </span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-gray-50/50">
                                <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Mã đơn</th>
                                <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Ngày đặt</th>
                                <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Thanh toán</th>
                                <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Trạng thái</th>
                                <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest text-right">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach($listOrder as $order)
                                <tr class="hover:bg-gray-50/50 transition-colors group">
                                    <td class="px-8 py-6">
                                        <span class="text-sm font-black text-gray-900">#{{ $order->id }}</span>
                                    </td>
                                    <td class="px-8 py-6">
                                        <span class="text-xs font-bold text-gray-500">{{ $order->created_at->format('d/m/Y H:i') }}</span>
                                    </td>
                                    <td class="px-8 py-6">
                                        <span class="text-[10px] font-black text-gray-400 uppercase tracking-tighter block mb-1">{{ $order->payment_type }}</span>
                                        <span class="px-2 py-0.5 rounded-md text-[9px] font-black uppercase {{ $order->payment_status == 'PAID' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-600' }}">
                                            {{ mapStringIsPaid($order->payment_status) }}
                                        </span>
                                    </td>
                                    <td class="px-8 py-6">
                                        {!! mapOrderStatus($order->status) !!}
                                    </td>
                                    <td class="px-8 py-6 text-right">
                                        <a href="{{ route('web.order_detail', $order->id) }}" 
                                           class="inline-flex items-center justify-center w-10 h-10 bg-gray-100 text-gray-400 rounded-xl hover:bg-green-900 hover:text-white transition-all shadow-sm">
                                            <i class="fa fa-eye text-xs"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if($listOrder->isEmpty())
                    <div class="p-20 text-center">
                        <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-6 text-gray-200">
                            <i class="fa fa-box-open text-3xl"></i>
                        </div>
                        <p class="text-sm font-bold text-gray-400 uppercase tracking-widest">Bạn chưa có đơn hàng nào</p>
                        <a href="{{ route('web.index') }}" class="inline-block mt-6 px-8 py-3 bg-green-900 text-white rounded-xl font-black text-[10px] tracking-widest uppercase hover:bg-black transition-all">
                            Mua sắm ngay
                        </a>
                    </div>
                @endif

                <div class="p-8 border-t border-gray-50">
                    {{ $listOrder->appends(request()->input())->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
