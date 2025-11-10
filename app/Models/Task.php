<?php

namespace App\Models;

use Database\Factories\TaskFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'title',
        'description',
        'status',
        'due_date',
        'user_id'
    ];
    protected $table = 'task';
    const CHUA_LAM = 0;
    const DANG_LAM = 1;
    const DA_XONG = 2;

    protected static function newFactory()
    {
        return TaskFactory::new();
    }

    // định nghĩa user
    public function user(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
