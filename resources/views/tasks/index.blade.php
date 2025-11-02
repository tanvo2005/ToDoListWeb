@extends('tasks.layout')

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Danh sách công việc</h5>
        <div style="display: inline-flex">
            {{-- sử lí việc thực hiện lọc thì mới hiển thị button xoá bộ lọc --}}
            @if (request()->has('search') || request()->has('status') || request()->has('locTheo')){{-- nếu mà thực  hiện các chức năng tìm kiếm, lọc sắp xếp --}}
                <a href="{{ route('tasks.index') }}" class="btn btn-sm btn-warning" style="margin-right: 5px">Xoá Bộ Lọc</a>
            @endif
            <a href="{{ route('tasks.create') }}" class="btn btn-sm btn-primary" style="margin-right: 5px">+ Thêm Task</a>
            <form action="{{ route('destoryAll') }}" method="POST" onsubmit="return confirm('Bạn có chắc muốn xoá tất cả không?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger btn-sm">Xoá Tất Cả</button>
            </form>
        </div>
    </div>
    <div class="card-body">
        @if (session('message'))
           <div class="alert alert-success">{{ session('message') }}</div> 
        @endif
        
        <form action="" method="GET">
            <div class="row g-3">

                <div class="col-md-4">
                    <input type="search" 
                        class="form-control" 
                        id="search" 
                        name="search" 
                        placeholder="Tìm kiếm Task..."
                        value="{{ request('search') }}">
                </div>

                <div class="col-md-3">
                    <select id="status" name="status" class="form-select" aria-placeholder="Trạng thái">
                    <option value="">-- Tất cả trạng thái --</option>
                    
                    <option value="0" @if(request('status') === 0) selected @endif >Chưa làm</option>
                    <option value="1" @if(request('status') === 1) selected @endif>Đang làm</option>
                    <option value="2" @if(request('status') === 2) selected @endif>Hoàn thành</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <select id="sort_by" name="locTheo" class="form-select" aria-placeholder="Sắp xếp theo">
                    <option value="">-- Sắp xếp theo --</option>
                    <option value="ngayTaoMoi" @if(request('locTheo') === 'ngayTaoMoi') selected @endif>Ngày tạo mới nhất</option>
                    <option value="ngayTaoLau" @if(request('locTheo') === 'ngayTaoLau') selected @endif>Ngày tạo cũ nhất</option>
                    <option value="sapDenHan" @if(request('locTheo') === 'sapDenHan') selected @endif>Sắp đến hạn chót</option>
                    <option value="chuaDenHan" @if(request('locTheo') === 'chuaDenHan') selected @endif>Chưa đến hạn</option>
                    </select>
                </div>

                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-funnel-fill"></i> Lọc</button>
                </div>
            </div>
        </form>
    
        <table class="table table-hover align-middle">
            <thead>
                <tr>
                    <th>STT</th>
                    <th>Công Việc</th>
                    <th>Hạn Chót</th>
                    <th>Trạng Thái</th>
                    <th></th>
                    <th>Ngày Tạo</th>
                    <th>Người Tạo</th>
                    <th class="text-end">Hành động</th>
                </tr>
            </thead>
            <tbody>
                <!-- Demo static -->
                @forelse ($tasks as $task)  
                    <tr data-id="{{ $task->id }}"><!-- lấy cái id -->
                        <td>{{ $task->id }}</td>
                        <td class="@if($task->status === 2)text-decoration-line-through text-muted @endif ">{{ $task->title }}</td>
                        <td>{{ $task->due_date }}</td>
                        <td>
                            {{-- có ba trạng thái 0 1 2 --}}
                            @switch( $task->status )
                                @case(0)
                                    <span class="badge bg-primary badge-status">Chưa bắt đầu</span>
                                    @break
                                @case(1)
                                    <span class="badge bg-warning badge-status">Đang làm</span>
                                    @break
                                @case(2)
                                    <span class="badge bg-success badge-status">Hoàn thành</span>
                                    @break
                                @default
                                    @break
                                
                                    
                            @endswitch
                            
                        </td>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown">Đổi Trạng Thái</button>
                                <ul class="dropdown-menu">
                                    <li><a href="" class="dropdown-item change-status " data-status="0">Chưa bắt đầu</a></li>
                                    <li><a href="" class="dropdown-item change-status " data-status="1">Đang làm</a></li>
                                    <li><a href="" class="dropdown-item change-status " data-status="2">Hoàn thành</a></li>
                                </ul>
                            </div>
                        </td>
                        <td>{{ $task->created_at->format('Y-m-d') }}</td>
                        <td>{{ $task->user->name }}</td>
                        <td class="text-end">
                            <a href="{{ route('tasks.show', $task->id) }}" class="btn btn-sm btn-info text-white">Xem</a>
                            <a href="{{ route('tasks.edit', $task->id) }}" class="btn btn-sm btn-warning">Sửa</a>
                            <form action="{{ route('tasks.destroy', $task->id) }}" method="POST"
                                style="display: inline-block;" onsubmit="return confirm('Bạn có chắc chắn muốn xoá task này không ?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger">Xóa</button>
                            </form>
                            
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" align="center">Hiện Tại Chưa Có Task Nào ! </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div>{{ $tasks->links('vendor.pagination.bootstrap-5') }}</div>
        
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function(){
        
        // Bắt sự kiện click
        $('.change-status').click(function(e){
            e.preventDefault(); 
            
            // --- BẮT ĐẦU LOGIC ---
            // TOÀN BỘ LOGIC PHẢI Ở TRONG NÀY

            const linkClicked = $(this); 
            const taskRow = linkClicked.closest('tr');
            const taskId = taskRow.data('id');
            const newStatus = linkClicked.data('status');

            // Chỉ gọi Ajax KHI click
            $.ajax({
                url: `/task/${taskId}/status`, // Bây giờ taskId sẽ có giá trị (ví dụ: 1, 2, 3)
                type: 'PATCH',
                data: {
                    status: newStatus,
                    _token: "{{ csrf_token() }}"
                }, 
                success: function(response) {
                    console.log('Cập nhật thành công!');
                    if(response.success){
                        const badge = taskRow.find('.badge-status');
                        let badgeText = '';
                        let badgeClass = 'badge ';
                        switch(response.status){
                            case '0':
                                badgeText = 'Chưa bắt đầu';
                                badgeClass += 'bg-primary';
                                break;
                            case '1':
                                badgeText = 'Đang làm';
                                badgeClass += 'bg-warning';
                                break;
                            case '2':
                                badgeText = 'Hoàn thành';
                                badgeClass += 'bg-success';
                                break;
                            default:
                                break;
                        }
                        // upadeta lại các tên class và text
                        // badge-status để sau này có thể update lại
                        badge.attr('class', badgeClass + ' badge-status').text(badgeText);

                        toastr.success(response.message);
                    }
                    //window.location.reload(); 
                }, 
                error: function(error) {
                    toastr.error('Cập Nhật Trạng Thái Thất Bại !');
                    //console.error('Lỗi khi cập nhật:', error);
                    //alert('Đã xảy ra lỗi.');
                }
            }); // Kết thúc $.ajax
            
            // --- KẾT THÚC LOGIC ---

        }); // Kết thúc hàm .click
        
        // KHÔNG ĐỂ BẤT KỲ CODE AJAX NÀO Ở ĐÂY

    }); // Kết thúc $(document).ready
</script>
@endpush
