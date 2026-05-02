<div class="row">
    <div class="col-12 card mt-3 p-3">
        <input type="hidden" id="id" name="id" value="{{ $product->id ?? '' }}">

        @if(!empty($product))
            <input type="hidden" id="id" name="type" value="{{ $product->type }}">
        @else
            <input type="hidden" id="id" name="type" value="{{ request()->get('type') ?? 'simple' }}">
        @endif

        <div class="form-group pt-3">
            <label for="name">Tên sản phẩm @include('admin.include.required_icon')</label>
            <input type="text" name="name" class="form-control" value="{{ $product->name ?? '' }}">
            <p class="alert alert-danger tag-error" id="name-error"></p>
        </div>

        <div class="form-group pt-3">
            <label for="category_id">Chuyên mục @include('admin.include.required_icon')</label>
            <select name="category_id" class="form-control form-select">
                <option value="">---</option>
                @foreach($listCategory as $category)
                    @if(!empty($product->category_id) && $category->id == $product->category_id)
                        <option selected value="{{ $category->id }}">{{ $category->name }}</option>
                    @else
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endif
                @endforeach
            </select>
            <p class="alert alert-danger tag-error" id="category_id-error"></p>
        </div>

        <div class="form-group pt-3">
            <label for="category_id">Danh sách hình</label>
            <div id="container-images" class="row mb-3">
                @if(!empty($product) && !empty($product->listImage))
                    @foreach($product->listImage as $image)
                        @include('admin.product.image_item', ['imageUrl' => $image->getImage(), 'id' => $image->id])
                    @endforeach
                @endif
            </div>
            <div>
                <button id="btn-add-image" class="btn btn-small btn-primary" type="button">
                    Thêm
                </button>
            </div>
            <p class="alert alert-danger tag-error" id="category_id-error"></p>
        </div>

        <div class="form-group pt-3">
            <label for="content">Mô tả</label>
            <textarea name="description" cols="30" rows="10" class="form-control">{{ $product->description ?? '' }}</textarea>
            <p class="alert alert-danger tag-error" id="description-error"></p>
        </div>

        @if((!empty($product) && $product->type == 'simple') || (empty($product) && empty(request()->type)))
            <div class="form-group pt-3">
                <label for="email">Giá bán @include('admin.include.required_icon')</label>
                <input type="text" name="price" class="form-control" value="{{ $product->price ?? '' }}">
                <p class="alert alert-danger tag-error" id="price-error"></p>
            </div>

            <div class="form-group pt-3">
                <label for="password">Số lượng @include('admin.include.required_icon')</label>
                <input type="text" name="quantity" class="form-control" value="{{ $product->quantity ?? '' }}">
                <p class="alert alert-danger tag-error" id="quantity-error"></p>
            </div>

            @if(!empty($product))
            <div class="form-group pt-3">
                <p>Số lượng còn lại: {{ $product->getQuantityActive() }}</p>
                <p>Số lượng đã bán: {{ $product->quantity - $product->getQuantityActive() }}</p>
            </div>
            @endif
        @endif

        <div class="form-group pt-3">
            <img src="" width="256px" class="img-preview">
            <label for="image">Ảnh sản phẩm</label>
            <input type="file" name="image" id="image" class="form-control">
            @if(!empty($product) && !empty($product->getImage()))
                <img src="{{ $product->getImage() }}" alt="" width="128px">
            @endif
            <p class="alert alert-danger tag-error" id="image-error"></p>
        </div>

        <div class="form-group pt-3">
            <label for="status">Trạng thái: @include('admin.include.required_icon')</label>
            <select class="form-group p-lg-2" name="status">
                <option value="1" @if(!empty($product) && $product->status == 1) selected @endif>On</option>
                <option value="0" @if(!empty($product) && $product->status == 0) selected @endif>Off</option>
            </select>
            <p class="alert alert-danger tag-error" id="status-error"></p>
        </div>
    </div>
    
</div>



