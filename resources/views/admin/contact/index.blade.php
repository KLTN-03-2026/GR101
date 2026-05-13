@extends('layouts.master_admin')

@section('content')
    <div class="pagetitle">
        <h1>Danh sách liên hệ</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item active">Danh sách</li>
                <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">Tổng quan</a></li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <div class="card">
        <div class="card-body pt-3">
            <table class="table table-hover" style="text-align: center">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Họ tên</th>
                        <th>Email</th>
                        <th>Tiêu đề</th>
                        <th>Trạng thái</th>
                        <th>Ngày gửi</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($contacts as $contact)
                        <tr style="{{ !$contact->is_read ? 'font-weight: bold; background-color: #f8f9fa;' : '' }}">
                            <td>{{ $contact->id }}</td>
                            <td>{{ $contact->name }}</td>
                            <td>{{ $contact->email }}</td>
                            <td>{{ $contact->subject }}</td>
                            <td>
                                @if($contact->is_read)
                                    <span class="badge bg-success">Đã xem</span>
                                @else
                                    <span class="badge bg-warning text-dark">Chưa xem</span>
                                @endif
                            </td>
                            <td>{{ $contact->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                <ul class="list-inline m-0">
                                    <li class="list-inline-item">
                                        <a href="{{ route('admin.contacts.show', $contact->id) }}" class="btn btn-info btn-sm rounded-0 text-white" title="Xem chi tiết"><i class="bi bi-eye"></i></a>
                                    </li>
                                    <li class="list-inline-item">
                                        <form action="{{ route('admin.contacts.destroy', $contact->id) }}" method="post" onsubmit="return confirm('Bạn có chắc muốn xóa liên hệ này?');">
                                            @csrf
                                            @method('delete')
                                            <button type="submit" class="btn btn-danger btn-sm rounded-0"><i class="bi bi-trash"></i></button>
                                        </form>
                                    </li>
                                </ul>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="mt-3">
                {{ $contacts->links() }}
            </div>
        </div>
    </div>
@endsection
