<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRquest;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)// để phục vụ tìm kiếm hàm index phải gọi Request
    {
        // tron trường hợp truy suất phức tạp thì dùng build query để truy xuất dữ liệu
        $query = Task::query();

        // buile query dugnf tốt cho các chức năng tìm kiếm và sắp xếp
        // tìm kiếm
        if($request->filled('search')){// nếu trong ô tìm kiếm có giá trị 
            $query->where(function ($subquery) use($request) {
                $subquery->where('title', 'LIKE', '%'. $request->search . '%') // truy vấn từ tiêu đề
                ->orWhere('description', 'LIKE', '%'. $request->search .'%');// truy vấn từ mô tả
            });
        }

        // lọc theo trạng thái 
        //$request->filled('status') : nếu trạng thái status được gửi lên
        //in_array($request->filled('status'),[Task::CHUA_LAM, Task::DANG_LAM, Task::DA_XONG]): nếu giá trị status khi được gửi lên nằm trong mảng có 3 trạng thái
        if($request->filled('status') && in_array($request->filled('status'),[Task::CHUA_LAM, Task::DANG_LAM, Task::DA_XONG])){
            $query->where('status', $request->status);
        }

        // sắp xếp theo
        switch($request->locTheo){
            case('sapDenHan'):
                $query->orderBy('due_date', 'ASC');
                break;
            case('chuaDenHan'):
                $query->orderBy('due_date', 'DESC');
                break;
            case('ngayTaoMoi'):
                $query->orderBy('created_at', 'ASC');
                break;
            case('ngayTaoLau'):
                $query->orderBy('created_at', 'DESC');
                break;
            default:
                $query->orderBy('id', 'DESC'); // nếu không có các trường trên thì lấy những thằng mới nhất
        }


        $tasks = $query->with('user')->paginate(10)->appends($request->all());
        // khi lọc dữ liệu mà nhấn sang trang khác thì nó sẽ không lưu dữ các request cũ do đó khi sang trang
        // mới nó sẽ bị mất tính nắng lọc cách khắc phục là thêm appends($request->all()); để nó dữ lại 
        //tất cả các request

        //$tasks = Task::all(); vid dữ liệu  nhiều nên giới hạn dữ liệu 10 dòng để phân trang
        //$tasks = Task::with('user')->orderBy('due_date')->paginate(10);
        return view('tasks.index', ['tasks'=>$tasks]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('tasks.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRquest $request)
    {
        $srores = Task::create([
            'title'=>$request->get('title'),
            'description'=>$request->get('description'),
            'due_date'=>$request->get('due_date'),
            'status'=>$request->get('status'),
            'user_id'=>Auth::id()
        ]);

        return redirect()->route('tasks.index')->with('message', 'Đã Tạo Task Thành Công !');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $tasks = Task::findOrFail($id);
        return view('tasks.show', ['tasks'=>$tasks]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $tasks = Task::findOrFail($id);
        return view('tasks.edit', ['tasks'=>$tasks]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreRquest $request, string $id)
    {
        $tasks = Task::findOrFail($id);
        $tasks->update([
            'title'=>$request->get('title'),
            'description'=>$request->get('description'),
            'due_date'=>$request->get('due_date'),
            'status'=>$request->get('status')
        ]);
        return redirect()->route('tasks.index')->with('message', 'Cập Nhật Task Thành Công !');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $tasks = Task::findOrFail($id);
        $tasks->delete();
        return redirect()->route('tasks.index')->with('message', 'Đã Xoá Task Thành Công !');
    }

    public function destoryAll(){
        Task::truncate();
        return redirect()->route('tasks.index')->with('message','Đã Xoá Thành Công !');
    }

    public function updateStatus(Request $request, $id){
        $status = $request->validate([
            'status'=>'required|in:0,1,2'
        ]);

        $task = Task::findOrFail($id);
        $task->update([
            'status'=> $status['status']
        ]);
        // vì phải gọi api nên phải dùng respon
        return response()->json([
            'success'=>true,
            'message'=>'Cập nhật trạng thái thành công !',
            'status'=>$task->status
        ]);
    }

    public function trash(Request $request){
        // tron trường hợp truy suất phức tạp thì dùng build query để truy xuất dữ liệu
        $query = Task::query();

        // buile query dugnf tốt cho các chức năng tìm kiếm và sắp xếp
        // tìm kiếm
        if($request->filled('search')){// nếu trong ô tìm kiếm có giá trị 
            $query->where(function ($subquery) use($request) {
                $subquery->where('title', 'LIKE', '%'. $request->search . '%') // truy vấn từ tiêu đề
                ->orWhere('description', 'LIKE', '%'. $request->search .'%');// truy vấn từ mô tả
            });
        }

        // lọc theo trạng thái 
        //$request->filled('status') : nếu trạng thái status được gửi lên
        //in_array($request->filled('status'),[Task::CHUA_LAM, Task::DANG_LAM, Task::DA_XONG]): nếu giá trị status khi được gửi lên nằm trong mảng có 3 trạng thái
        if($request->filled('status') && in_array($request->filled('status'),[Task::CHUA_LAM, Task::DANG_LAM, Task::DA_XONG])){
            $query->where('status', $request->status);
        }

        // sắp xếp theo
        switch($request->locTheo){
            case('sapDenHan'):
                $query->orderBy('due_date', 'ASC');
                break;
            case('chuaDenHan'):
                $query->orderBy('due_date', 'DESC');
                break;
            case('ngayTaoMoi'):
                $query->orderBy('created_at', 'ASC');
                break;
            case('ngayTaoLau'):
                $query->orderBy('created_at', 'DESC');
                break;
            default:
                $query->orderBy('id', 'DESC'); // nếu không có các trường trên thì lấy những thằng mới nhất
        }


        $tasks = $query->onlyTrashed()->with('user')->paginate(10)->appends($request->all());
        // khi lọc dữ liệu mà nhấn sang trang khác thì nó sẽ không lưu dữ các request cũ do đó khi sang trang
        // mới nó sẽ bị mất tính nắng lọc cách khắc phục là thêm appends($request->all()); để nó dữ lại 
        //tất cả các request

        //$tasks = Task::all(); vid dữ liệu  nhiều nên giới hạn dữ liệu 10 dòng để phân trang
        //$tasks = Task::with('user')->orderBy('due_date')->paginate(10);
        return view('tasks.trash', ['tasks'=>$tasks]);
    }

    public function restore($id){
        Task::withTrashed()->findOrFail($id)->restore();
        return redirect()->route('tasks.index')->with('message', 'Khôi phục Task Thành Công !');
    }

    public function forceDelete($id){
        Task::withTrashed()->findOrFail($id)->forceDelete();
        return redirect()->route('tasks.index')->with('message', 'Xoá Task Thành Công !');
    }

    public function forceDeleteAll(){
        Task::onlyTrashed()->forceDelete();
        return redirect()->route('tasks.index')->with('message', 'Xoá tất cả Task Thành Công !');
    }
}
