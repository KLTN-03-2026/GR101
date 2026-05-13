@extends('layouts.master_user')

@section('content')
<div class="bg-gray-50 min-h-screen font-sans pb-20">
    <div class="breadcrumb-area py-10">
        <div class="container">
            <nav class="flex text-xs font-bold uppercase tracking-widest text-gray-400 gap-4">
                <a href="{{ route('web.index') }}" class="hover:text-green-900 transition-colors">Trang chủ</a>
                <i class="fa fa-chevron-right text-[8px] mt-0.5"></i>
                <a href="{{ route('web.list_order_of_user') }}" class="hover:text-green-900 transition-colors">Đơn hàng của tôi</a>
                <i class="fa fa-chevron-right text-[8px] mt-0.5"></i>
                <span class="text-gray-900">Chi tiết #{{ $order->id }}</span>
            </nav>
        </div>
    </div>

    <div class="container">
        @if(session('success'))
            <div class="max-w-3xl mx-auto mb-12 text-center bg-white rounded-[3rem] p-12 shadow-sm border border-green-50">
                <div class="w-20 h-20 bg-soft-green rounded-full flex items-center justify-center mx-auto mb-6 text-green-700 shadow-inner">
                    <i class="fa fa-check text-3xl"></i>
                </div>
                <h1 class="text-3xl font-black text-gray-900 mb-4 tracking-tight">Đặt hàng thành công!</h1>
                <p class="text-gray-400 font-bold uppercase text-[10px] tracking-[0.3em] mb-2">Mã đơn hàng của bạn là</p>
                <div class="text-2xl font-black text-green-900 tracking-widest mb-8">#{{ $order->id }}</div>
                <p class="text-sm text-gray-500 font-medium max-w-md mx-auto">
                    Cảm ơn bạn đã tin tưởng Nông Sản Xanh. Đơn hàng của bạn đang được chúng tôi chuẩn bị và sẽ sớm giao đến tay bạn.
                </p>
            </div>
        @endif

        <div class="row">
            <!-- Left: Order Items -->
            <div class="col-lg-8">
                <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden mb-8">
                    <div class="p-8 border-b border-gray-50 flex items-center justify-between">
                        <h2 class="text-xl font-black text-gray-900 tracking-tight">Sản phẩm đã đặt</h2>
                        <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">{{ count($order->Products) }} sản phẩm</span>
                    </div>

                    <div class="p-8 space-y-8">
                        @foreach($order->Products as $product)
                            <div class="flex items-center gap-6">
                                <div class="w-24 h-24 bg-gray-50 rounded-2xl p-3 flex-shrink-0">
                                    <img src="{{ $product->getImage() }}" class="w-full h-full object-contain">
                                </div>
                                <div class="flex-1 space-y-1">
                                    <h4 class="text-base font-black text-gray-900">{{ $product->getName() }}</h4>
                                    <p class="text-xs font-bold text-gray-400 uppercase tracking-tighter">{{ formatVnd($product->pivot->price) }} × {{ $product->pivot->quantity }}</p>
                                </div>
                                <div class="text-right">
                                    <div class="text-base font-black text-gray-900">{{ formatVnd($product->pivot->price * $product->pivot->quantity) }}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Shipping Tracking (Optional UI) -->
                <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 p-8 space-y-8">
                    <h2 class="text-xl font-black text-gray-900 tracking-tight">Trạng thái xử lý</h2>
                    <div class="flex items-center justify-between px-4">
                        @php 
                            $statusSteps = [
                                'PENDING' => ['label' => 'Chờ duyệt', 'icon' => 'fa-clock'],
                                'CONFIRMED' => ['label' => 'Đã xác nhận', 'icon' => 'fa-check-circle'],
                                'DELIVERY' => ['label' => 'Đang giao', 'icon' => 'fa-truck'],
                                'SUCCESS' => ['label' => 'Thành công', 'icon' => 'fa-box-open']
                            ];
                            $foundCurrent = false;
                        @endphp

                        @foreach($statusSteps as $key => $step)
                            <div class="flex flex-col items-center gap-3 relative z-10">
                                <div class="w-12 h-12 rounded-full flex items-center justify-center {{ $order->status == $key ? 'bg-green-900 text-white shadow-xl' : ($foundCurrent ? 'bg-gray-100 text-gray-300' : 'bg-soft-green text-green-700') }}">
                                    <i class="fa {{ $step['icon'] }} text-lg"></i>
                                </div>
                                <span class="text-[10px] font-black uppercase tracking-tighter {{ $order->status == $key ? 'text-green-900' : 'text-gray-400' }}">{{ $step['label'] }}</span>
                                @php if($order->status == $key) $foundCurrent = true; @endphp
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Right: Order Info & Totals -->
            <div class="col-lg-4 mt-10 lg:mt-0">
                <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 p-10 space-y-10 sticky top-10">
                    <!-- Delivery Info -->
                    <div class="space-y-6">
                        <h3 class="text-xs font-black text-gray-400 uppercase tracking-widest border-b border-gray-50 pb-4">Giao hàng tới</h3>
                        <div class="space-y-4">
                            <div class="flex gap-4">
                                <i class="fa fa-user text-green-700 mt-1"></i>
                                <div>
                                    <p class="text-sm font-black text-gray-900">{{ $order->name }}</p>
                                    <p class="text-xs font-bold text-gray-400">{{ $order->phone }}</p>
                                </div>
                            </div>
                            <div class="flex gap-4">
                                <i class="fa fa-map-marker-alt text-green-700 mt-1"></i>
                                <p class="text-sm font-bold text-gray-600 leading-relaxed">{{ $order->address }}</p>
                            </div>
                            @if($order->note)
                                <div class="flex gap-4 bg-gray-50 p-4 rounded-2xl border border-gray-100 italic">
                                    <i class="fa fa-comment-alt text-gray-300 mt-1"></i>
                                    <p class="text-xs font-bold text-gray-500">{{ $order->note }}</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Payment Info -->
                    <div class="space-y-6">
                        <h3 class="text-xs font-black text-gray-400 uppercase tracking-widest border-b border-gray-50 pb-4">Thanh toán</h3>
                        <div class="space-y-4">
                            <div class="flex justify-between items-center text-sm">
                                <span class="font-bold text-gray-400 uppercase tracking-widest text-[10px]">Phương thức</span>
                                <span class="font-black text-gray-900 uppercase">{{ $order->payment_type }}</span>
                            </div>
                            <div class="flex justify-between items-center text-sm">
                                <span class="font-bold text-gray-400 uppercase tracking-widest text-[10px]">Trạng thái</span>
                                <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase {{ $order->payment_status == 'PAID' ? 'bg-soft-green text-green-800' : 'bg-red-50 text-red-600' }}">
                                    {{ mapStringIsPaid($order->payment_status) }}
                                </span>
                            </div>
                            
                            @if($order->payment_status == 'UNPAID' && $order->payment_type != 'COD' && $order->status != 'CANCEL')
                                <div class="pt-4">
                                    @if($order->payment_type == 'VNPAY')
                                        <a href="{{ route('web.vnpay.create', ['amount' => $order->total(), 'order_id' => $order->id]) }}" 
                                           class="w-full bg-blue-600 text-white py-4 rounded-2xl font-black text-xs tracking-widest hover:bg-blue-700 transition-all flex items-center justify-center gap-3 shadow-lg">
                                            <i class="fa fa-redo"></i> THANH TOÁN LẠI
                                        </a>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Totals -->
                    <div class="space-y-4 pt-8 border-t border-gray-100">
                        <div class="flex justify-between items-center text-sm">
                            <span class="font-bold text-gray-400 uppercase tracking-widest text-[10px]">Tiền hàng</span>
                            <span class="font-black text-gray-900">{{ formatVnd($order->total(false) - $order->shipping_fee) }}</span>
                        </div>
                        <div class="flex justify-between items-center text-sm">
                            <span class="font-bold text-gray-400 uppercase tracking-widest text-[10px]">Phí vận chuyển</span>
                            <span class="font-black text-gray-900">+ {{ formatVnd($order->shipping_fee) }}</span>
                        </div>
                        @if($order->discount > 0)
                            <div class="flex justify-between items-center text-sm">
                                <span class="font-bold text-orange-400 uppercase tracking-widest text-[10px]">Khuyến mãi</span>
                                <span class="font-black text-orange-500">- {{ formatVnd($order->discount) }}</span>
                            </div>
                        @endif
                        <div class="flex justify-between items-center pt-6 border-t border-gray-50">
                            <span class="font-black text-gray-900 uppercase tracking-tighter text-base">Tổng tiền</span>
                            <span class="font-black text-2xl text-green-900 tracking-tighter">{{ formatVnd($order->total()) }}</span>
                        </div>
                    </div>

                    @if($order->status == 'PENDING')
                        <form action="{{ route('web.order_update_status', $order->id) }}" method="post" id="form-cancel">
                            @csrf
                            <input type="hidden" name="status" value="CANCEL">
                            <button type="button" onclick="confirmCancel()" class="w-full border-2 border-red-50 text-red-500 py-4 rounded-2xl font-black text-[10px] tracking-widest hover:bg-red-500 hover:text-white transition-all uppercase">
                                Hủy đơn hàng
                            </button>
                        </form>
                    @endif
                    
                    <a href="{{ route('web.index') }}" class="flex items-center justify-center gap-3 mt-4 text-xs font-black text-gray-400 hover:text-gray-900 transition-colors tracking-widest uppercase">
                        Quay lại trang chủ
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    function confirmCancel() {
        Swal.fire({
            title: 'Hủy đơn hàng?',
            text: "Bạn có chắc chắn muốn hủy đơn hàng này không?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#14532d',
            confirmButtonText: 'Đúng, hãy hủy!',
            cancelButtonText: 'Không, giữ lại'
        }).then((result) => {
            if (result.isConfirmed) {
                $.LoadingOverlay('show');
                $('#form-cancel').submit();
            }
        });
    }
</script>
@endsection
