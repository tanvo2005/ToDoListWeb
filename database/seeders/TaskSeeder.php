<?php

namespace Database\Seeders;

use App\Models\Task;
use Database\Factories\TaskFactory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('task')->truncate();// xoá toàn bộ dữ liệu cũ đi

        // gọi facetory để tạo dữ liẹu mẫu
        Task::factory(100)->create();
    }
}
