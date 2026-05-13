<div class="space-y-8 font-sans" id="filter-sidebar">
    <!-- Sorting -->
    <div class="bg-white rounded-[2rem] border border-gray-100 shadow-sm p-8">
        <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-6 px-2">Sắp xếp</h3>
        <div class="relative group">
            @php $currentSort = request()->get('sort_by', 'latest'); @endphp
            <select id="sort_by" class="w-full bg-white border border-gray-200 rounded-2xl px-6 text-sm font-bold text-black focus:ring-2 focus:ring-green-900/10 outline-none transition-all cursor-pointer block" style="color: #000 !important; height: 50px !important; line-height: normal !important;">
                <option value="latest" {{ $currentSort == 'latest' ? 'selected' : '' }}>Mới nhất</option>
                <option value="price_asc" {{ $currentSort == 'price_asc' ? 'selected' : '' }}>Giá: Thấp đến Cao</option>
                <option value="price_desc" {{ $currentSort == 'price_desc' ? 'selected' : '' }}>Giá: Cao đến Thấp</option>
            </select>
        </div>
    </div>

    <!-- Category Filter -->
    <div class="bg-white rounded-[2rem] border border-gray-100 shadow-sm p-8">
        <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-6 px-2">Lọc theo Danh mục</h3>
        <div class="space-y-4">
            @php $selectedCats = request()->get('category_ids', []); @endphp
            @foreach($listCategory as $cat)
                <label class="flex items-center gap-4 group cursor-pointer">
                    <div class="relative flex items-center">
                        <input type="checkbox" name="category_ids[]" value="{{ $cat->id }}" 
                               {{ (in_array($cat->id, $selectedCats) || (isset($category) && $category->id == $cat->id)) ? 'checked' : '' }}
                               class="filter-checkbox peer appearance-none w-6 h-6 border-2 border-gray-100 rounded-xl checked:bg-green-900 checked:border-green-900 transition-all shadow-sm">
                        <i class="fa fa-check absolute text-[10px] text-white opacity-0 peer-checked:opacity-100 left-1.5 transition-opacity"></i>
                    </div>
                    <span class="text-sm font-bold text-gray-600 group-hover:text-green-800 transition-colors">{{ $cat->name }}</span>
                </label>
            @endforeach
        </div>
    </div>

    <!-- Price Filter -->
    <div class="bg-white rounded-[2rem] border border-gray-100 shadow-sm p-8">
        <div class="flex items-center justify-between mb-8 px-2">
            <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Khoảng giá</h3>
            <span class="text-[10px] font-black text-green-700 bg-soft-green px-3 py-1 rounded-full">VNĐ</span>
        </div>
        
        <div class="px-2 mb-10">
            <div id="price-slider" class="h-1.5 bg-gray-100 rounded-full border-none shadow-inner"></div>
        </div>

        <div class="flex items-center justify-between gap-4">
            <div class="flex-1">
                <span class="block text-[8px] font-black text-gray-300 uppercase mb-1 ml-1">Từ</span>
                <div class="text-sm font-black text-gray-900 bg-gray-50 rounded-xl py-2 px-3 text-center" id="price-min-display">0</div>
            </div>
            <div class="w-4 h-px bg-gray-200 mt-4"></div>
            <div class="flex-1">
                <span class="block text-[8px] font-black text-gray-300 uppercase mb-1 ml-1">Đến</span>
                <div class="text-sm font-black text-gray-900 bg-gray-50 rounded-xl py-2 px-3 text-center" id="price-max-display">500k</div>
            </div>
        </div>

        <input type="hidden" id="min_price" value="0">
        <input type="hidden" id="max_price" value="1000000">

        <button type="button" onclick="applyFilters()" class="w-full bg-green-900 text-white py-4 rounded-2xl font-black text-[10px] tracking-widest hover:bg-black transition-all shadow-xl active:scale-95 uppercase mt-8">
            ÁP DỤNG BỘ LỌC
        </button>
    </div>
</div>

<style>
    /* Custom Styling for noUiSlider to match Modern Premium Theme */
    .noUi-target {
        background: #f3f4f6;
        border: none;
        box-shadow: inset 0 2px 4px rgba(0,0,0,0.05);
    }
    .noUi-connect {
        background: #14532d;
    }
    .noUi-horizontal .noUi-handle {
        width: 24px;
        height: 24px;
        right: -12px;
        top: -10px;
        border-radius: 50%;
        background: #fff;
        border: 4px solid #14532d;
        box-shadow: 0 4px 10px rgba(20, 83, 45, 0.2);
        cursor: pointer;
    }
    .noUi-handle:before, .noUi-handle:after {
        display: none;
    }
</style>

@section('js_attr_search')
<script>
    var slider = document.getElementById('price-slider');

    noUiSlider.create(slider, {
        start: [0, 500000],
        connect: true,
        range: {
            'min': 0,
            'max': 1000000
        },
        step: 10000,
        format: {
            to: function (value) {
                return Math.round(value);
            },
            from: function (value) {
                return value;
            }
        }
    });

    slider.noUiSlider.on('update', function (values, handle) {
        var min = values[0];
        var max = values[1];
        
        $('#min_price').val(min);
        $('#max_price').val(max);
        
        $('#price-min-display').text(new Intl.NumberFormat('vi-VN').format(min));
        $('#price-max-display').text(new Intl.NumberFormat('vi-VN').format(max));
    });

    function applyFilters() {
        const categoryIds = [];
        $('input[name="category_ids[]"]:checked').each(function() {
            categoryIds.push($(this).val());
        });

        const data = {
            search: '{{ $search ?? "" }}',
            category_ids: categoryIds,
            min_price: $('#min_price').val(),
            max_price: $('#max_price').val(),
            sort_by: $('#sort_by').val(),
            page: 1
        };

        fetchProducts(data);
    }

    function fetchProducts(data) {
        $('#filter-loader').removeClass('hidden');
        
        $.ajax({
            url: window.location.pathname,
            data: data,
            success: function(res) {
                $('#ajax-product-container').html(res.html);
                $('#total-products').text(res.total);
                
                const url = new URL(window.location);
                // Clear existing query params except base path
                url.search = '';
                
                Object.keys(data).forEach(key => {
                    if (data[key]) {
                        if (Array.isArray(data[key])) {
                            data[key].forEach(val => url.searchParams.append(key + '[]', val));
                        } else {
                            url.searchParams.set(key, data[key]);
                        }
                    }
                });
                window.history.pushState({}, '', url);
                
                $('#filter-loader').addClass('hidden');
                $('html, body').animate({
                    scrollTop: $("#ajax-product-container").offset().top - 150
                }, 500);
            },
            error: function() {
                $('#filter-loader').addClass('hidden');
                alert('Có lỗi xảy ra, vui lòng thử lại.');
            }
        });
    }

    function clearAllFilters() {
        $('input.filter-checkbox').prop('checked', false);
        slider.noUiSlider.set([0, 1000000]);
        $('#sort_by').val('latest');
        applyFilters();
    }

    $(document).ready(function() {
        $('.filter-checkbox, #sort_by').on('change', function() {
            applyFilters();
        });

        $(document).on('click', '.pagination a', function(e) {
            e.preventDefault();
            const url = new URL($(this).attr('href'));
            const page = url.searchParams.get('page');
            
            const categoryIds = [];
            $('input[name="category_ids[]"]:checked').each(function() {
                categoryIds.push($(this).val());
            });

            const data = {
                search: '{{ $search ?? "" }}',
                category_ids: categoryIds,
                min_price: $('#min_price').val(),
                max_price: $('#max_price').val(),
                sort_by: $('#sort_by').val(),
                page: page
            };

            fetchProducts(data);
        });
    });
</script>
@endsection
