<div class="form-group pt-3">
    <label for="name">Tên mã khuyến mãi @include('admin.include.required_icon')</label>
    <input type="text" name="name" class="form-control" value="{{ old('name', $coupon->name ?? request('coupon_code') ?? '') }}">
    @error('name')
        <p class="alert alert-danger">{{ $message }}</p>
    @enderror
</div>


<div class="form-group pt-3">
    <label for="name">Loại khuyến mãi @include('admin.include.required_icon')</label>

    @if(!empty($coupon))
        {{-- Khi sửa: hiển thị text, không cho thay đổi, dùng input hidden để gửi giá trị --}}
        <input type="text" class="form-control bg-light text-muted"
               value="{{ getListCouponType()[$coupon->type] ?? $coupon->type }}" readonly>
        <input type="hidden" name="type" value="{{ $coupon->type }}">
        <small class="text-muted"><i class="fa fa-info-circle"></i> Không thể thay đổi loại khuyến mãi sau khi đã tạo.</small>
    @else
        {{-- Khi tạo mới: cho phép chọn --}}
        <select name="type" class="form-control form-select">
            @foreach(getListCouponType() as $key => $value)
                <option value="{{ $key }}" @if(request('type') == $key) selected @endif>{{ $value }}</option>
            @endforeach
        </select>
    @endif

    @error('type')
        <p class="alert alert-danger">{{ $message }}</p>
    @enderror
</div>

<div class="form-group pt-3">
    <label for="name">Số tiền/phần trăm khuyến mãi @include('admin.include.required_icon')</label>
    <input type="text" name="discount" class="form-control" value="{{ old('discount', $coupon->discount ?? request('discount_value') ?? '') }}">
    @error('discount')
        <p class="alert alert-danger">{{ $message }}</p>
    @enderror
</div>

<div class="form-group pt-3">
    <label for="name">Số tiền giảm tối đa</label>
    <input type="text" name="discount_max" class="form-control" value="{{ $coupon->discount_max ?? '' }}">
    @error('discount_max')
        <p class="alert alert-danger">{{ $message }}</p>
    @enderror
</div>

<div class="form-group pt-3">
    <label for="name">Số lần sử dụng</label>
    <input type="text" name="number_use" class="form-control" @if(!empty($coupon)) disabled @endif value="{{ $coupon->number_use ?? '' }}">
    @if(!empty($coupon))
        <p>Còn lại: {{ getNumberUseFreeCoupon($coupon->id) }} </p>
    @endif
    @error('number_use')
        <p class="alert alert-danger">{{ $message }}</p>
    @enderror
</div>

<div class="form-group pt-3">
    <label for="name">Ngày bắt đầu</label>
    <input type="date" name="start" class="form-control" value="{{ $coupon->start ?? '' }}">
    @error('start')
        <p class="alert alert-danger">{{ $message }}</p>
    @enderror
</div>

<div class="form-group pt-3">
    <label for="name">Ngày kết thúc</label>
    <input type="date" name="end" class="form-control" value="{{ $coupon->end ?? '' }}">
    @error('end')
        <p class="alert alert-danger">{{ $message }}</p>
    @enderror
</div>



