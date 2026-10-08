@extends('layouts.master_admin')

@section('content')
    <div class="container-fluid pt-4 px-4">
        <div class="bg-light text-center rounded p-4">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h6 class="mb-0">Quản lý đánh giá sản phẩm</h6>
            </div>
            <div class="table-responsive">
                <table class="table text-start align-middle table-bordered table-hover mb-0">
                    <thead>
                        <tr class="text-dark">
                            <th scope="col">ID</th>
                            <th scope="col">Khách hàng</th>
                            <th scope="col">Sản phẩm</th>
                            <th scope="col">Số sao</th>
                            <th scope="col">Nội dung</th>
                            <th scope="col">Ngày gửi</th>
                            <th scope="col">Trạng thái</th>
                            <th scope="col">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($reviews as $review)
                            <tr>
                                <td>{{ $review->id }}</td>
                                <td>{{ $review->User->name ?? 'N/A' }}</td>
                                <td>
                                    <a href="{{ route('web.detail', $review->product_id) }}" target="_blank">
                                        {{ $review->Product->name ?? 'N/A' }}
                                    </a>
                                </td>
                                <td>
                                    <div class="text-warning">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="bi bi-star-fill {{ $i <= $review->rating ? 'text-warning' : 'text-secondary' }}"></i>
                                        @endfor
                                    </div>
                                </td>
                                <td>{{ Str::limit($review->comment, 50) }}</td>
                                <td>{{ $review->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    <button class="btn btn-sm {{ $review->status == 1 ? 'btn-success' : 'btn-secondary' }} toggle-status" 
                                            data-id="{{ $review->id }}">
                                        {{ $review->status == 1 ? 'Hiển thị' : 'Đang ẩn' }}
                                    </button>
                                </td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#replyModal{{ $review->id }}">
                                            Phản hồi
                                        </button>
                                        <form action="{{ route('admin.reviews.destroy', $review->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">Xóa</button>
                                        </form>
                                    </div>

                                    <!-- Reply Modal -->
                                    <div class="modal fade" id="replyModal{{ $review->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <form action="{{ route('admin.reviews.reply', $review->id) }}" method="POST">
                                                    @csrf
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Phản hồi đánh giá #{{ $review->id }}</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body text-start">
                                                        <p><strong>Khách hàng:</strong> {{ $review->User->name ?? 'N/A' }}</p>
                                                        <p><strong>Nội dung:</strong> {{ $review->comment }}</p>
                                                        <div class="form-group mt-3">
                                                            <label class="mb-2">Nội dung phản hồi:</label>
                                                            <textarea name="reply_content" class="form-control" rows="4" required>{{ $review->reply_content }}</textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                                                        <button type="submit" class="btn btn-primary">Gửi phản hồi</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $reviews->links() }}
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        $(document).ready(function() {
            $('.toggle-status').click(function() {
                const btn = $(this);
                const id = btn.data('id');
                
                $.ajax({
                    url: `{{ url('admin/reviews') }}/${id}`,
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        _method: 'PUT'
                    },
                    success: function(res) {
                        if(res.success) {
                            if(res.status == 1) {
                                btn.removeClass('btn-secondary').addClass('btn-success').text('Hiển thị');
                            } else {
                                btn.removeClass('btn-success').addClass('btn-secondary').text('Đang ẩn');
                            }
                        }
                    }
                });
            });
        });
    </script>
@endsection
