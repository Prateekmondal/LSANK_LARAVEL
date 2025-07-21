<?php

use App\Http\Controllers\AjaxCallController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\JcrController;
use App\Http\Controllers\ContactController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

// Route::get('/dashboard', function () {
// $user = Auth::user();
//     $jcrs = Auth::user()->jcrs()->with(['users', 'logs', 'explosives'])->orderBy('arrivalOffice_date', 'desc')
//             ->orderBy('arrivalOffice_time', 'desc')->paginate(10);
//     return view('dashboard', ['jcrs'=>$jcrs, 'user'=>$user]);
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile/update_avatar', [ProfileController::class, 'update_avatar'])->name('profile.update_avatar');
    Route::delete('/profile/delete', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware('auth')->group(function () {
    Route::get('jcr/add', [JcrController::class,'index'])->name('jcr.add');
    Route::post('jcr/add', [JcrController::class,'add'])->name('jcr.save');
    Route::get('jcr/view', [JcrController::class,'view'])->name('jcr.view');
    Route::get('/dashboard', [JcrController::class,'dashboardView'])->name('dashboard');
    Route::get('jcr/show', [JcrController::class,'show'])->name('jcr.show');
    // Route::post('jcr/show', [JcrController::class,'show'])->name('jcr.show');
    Route::post('jcr/update', [JcrController::class,'update'])->name('jcr.update');
    Route::get('jcr/download', [JcrController::class,'download'])->name('jcr.download');
    // Route::put('jcr/edit', [JcrController::class,'update'])->name('jcr.edit');
});

Route::middleware('auth')->prefix('jcr')->group(function () {
    Route::get('ajaxcalls/users', [AjaxCallController::class, 'getUsers'])->name('ajax.getusers');
    Route::get('ajaxcalls/cableinfo', [AjaxCallController::class, 'getCableinfo'])->name('ajax.getcableinfo');
    Route::get('ajaxcalls/jobno', [AjaxCallController::class, 'getJobNo'])->name('ajax.getjobno');
});

Route::post('contact-us', [ContactController::class, 'store'])->name('contact.us.store');

require __DIR__.'/auth.php';
