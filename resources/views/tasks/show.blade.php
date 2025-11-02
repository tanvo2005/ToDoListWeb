@extends('tasks.layout')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">📄 Chi tiết công việc</h5>
                <a href="{{ route('tasks.index') }}" class="btn btn-sm btn-secondary">⬅ Quay lại</a>
            </div>
            <div class="card-body">
                <h4 class="fw-bold mb-3">{{ $tasks->title }}</h4>
                <p class="text-muted"><strong>Mô tả:</strong></p>
                <p>{{ $tasks->description ?? 'chưa có mô tả '}}</p>
                <p><strong>Hạn chót:</strong> <span class="badge bg-danger">{{ $tasks->due_date }}</span></p>
                <p><strong>Trạng thái:</strong>
                    @switch($tasks->status)
                        @case(0)
                            <span class="badge bg-primary">Chưa bắt đầu</span>
                            @break
                        @case(1)
                            <span class="badge bg-warning">Đang làm</span>
                            @break
                        @case(2)
                            <span class="badge bg-success">Hoàn thành</span>
                            @break
                        @default
                            @break
                    @endswitch
                    </p>

                <div class="mt-4 d-flex justify-content-end">
                    <a href="{{ route('tasks.edit', $tasks->id) }}" class="btn btn-warning me-2">✏️ Sửa</a>
                    <form action="{{ route('tasks.destroy', $tasks->id) }}" method="POST"
                            style="display: inline-block;" onsubmit="return confirm('Bạn có chắc chắn muốn xoá task này không ?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger me-2">Xóa</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
