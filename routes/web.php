<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

Route::middleware('auth')->group(function (){
    Route::get('/', [TaskController::class, 'index'])->name('tasks.index');

    Route::get('/trash', [TaskController::class, 'trash'])->name('tasks.trash');
    Route::post('/{id}/restore', [TaskController::class, 'restore'])->name('tasks.restore');
    Route::delete('/{id}/forceDelete', [TaskController::class, 'forceDelete'])->name('tasks.forceDelete');
    Route::delete('/forceDeleteAll', [TaskController::class, 'forceDeleteAll'])->name('tasks.forceDeleteAll');




    Route::get('/create', [TaskController::class , 'create'])->name('tasks.create');
    Route::post('/', [TaskController::class, 'store'])->name('tasks.store');

    Route::delete('/destoryAll', [TaskController::class, 'destoryAll'])->name('destoryAll');
    Route::patch('/task/{id}/status', [TaskController::class, 'updateStatus'])->name('tasks.updateStatus');
    //PATCH : được dùng để update 1 phần của thuộc tính trong trường hợp này dùng để upadetet trạng thái
   
    Route::get('/{task}', [TaskController::class, 'show'])->name('tasks.show');
    Route::get('/{task}/edit', [TaskController::class, 'edit'])->name('tasks.edit');
    Route::put('/{task}', [TaskController::class, 'update'])->name('tasks.update');
    Route::delete('/{task}',[TaskController::class, 'destroy'])->name('tasks.destroy');
    
});
