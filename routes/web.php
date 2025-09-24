<?php

use App\Http\Controllers\AjaxCallController;
use App\Http\Controllers\ExternalSignerController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\JcrController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ChecklistController;
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

// JCR Routes - Protected by auth middleware
Route::middleware('auth')->group(function () {

    // JCR Resource Routes (CRUD operations)
    Route::resource('jcr', JcrController::class);

    // Additional JCR routes with specific permissions
    Route::get('jcr/create', [JcrController::class, 'create'])->name('jcr.create');
    Route::post('jcr', [JcrController::class, 'store'])->name('jcr.store');

    Route::get('jcr/{jcr}/edit', [JcrController::class, 'edit'])->name('jcr.edit');
    Route::put('jcr/{jcr}', [JcrController::class, 'update'])->name('jcr.update');

    Route::delete('jcr/{jcr}', [JcrController::class, 'destroy'])->name('jcr.destroy');

    // JCR Preview and Signature Routes
    Route::get('jcr/{jcr}/preview', [JcrController::class, 'preview'])->name('jcr.preview');
    Route::post('jcr/{jcr}/submit', [JcrController::class, 'submit'])->name('jcr.submit');

    // Party Chief Signature Route (requires party_chief role)
    Route::middleware(['can:sign_as_party_chief'])->group(function () {
        Route::post('jcr/{jcr}/party-chief-sign', [JcrController::class, 'partyChiefSign'])->name('jcr.party-chief.sign');
    });

    // Operation Incharge Signature Route (requires operation_incharge role)
    Route::middleware(['can:sign_as_operation_incharge'])->group(function () {
        Route::post('jcr/{jcr}/operation-incharge-sign', [JcrController::class, 'operationInchargeSign'])->name('jcr.operation-incharge.sign');
    });
    // Route::resource('jcr', JcrController::class);
    // Route::get('jcr/{jcr}/preview', [JcrController::class, 'preview'])->name('jcr.preview');
    // Route::post('jcr/{jcr}/submit', [JcrController::class, 'submit'])->name('jcr.submit');
    // Route::get('jcr/add', [JcrController::class, 'index'])->name('jcr.add');
    // Route::post('jcr/add', [JcrController::class, 'add'])->name('jcr.save');
    // Route::get('jcr/view', [JcrController::class, 'view'])->name('jcr.view');
    Route::get('/dashboard', [JcrController::class, 'dashboardView'])->name('dashboard');
    // Route::get('jcr/show/{id}', [JcrController::class, 'show'])->name('jcr.show');
    // Route::post('jcr/show', [JcrController::class,'show'])->name('jcr.show');
    // Route::post('jcr/update', [JcrController::class, 'update'])->name('jcr.update');
    Route::get('jcr/download', [JcrController::class, 'download'])->name('jcr.download');
    // Route::get('jcr/{jcr}/edit', [JcrController::class,'edit'])->name('jcr.edit');
    Route::get('/jcr/{jcr}/print', [JCRController::class, 'print'])->name('jcr.print');
});

Route::middleware('auth')->prefix('jcr')->group(function () {
    Route::get('ajaxcalls/users', [AjaxCallController::class, 'getUsers'])->name('ajax.getusers');
    Route::get('ajaxcalls/cableinfo', [AjaxCallController::class, 'getCableinfo'])->name('ajax.getcableinfo');
    Route::get('ajaxcalls/jobno', [AjaxCallController::class, 'getJobNo'])->name('ajax.getjobno');
    Route::post('/checklists/check-group', [ChecklistController::class, 'checkGroupCompletion'])->name('checklists.checkGroup');
});

Route::post('contact-us', [ContactController::class, 'store'])->name('contact.us.store');

Route::prefix('checklists')->middleware('auth')->group(function () {
    Route::get('/', [ChecklistController::class, 'index'])->name('checklists.index');
    Route::get('/create/{type}', [ChecklistController::class, 'create'])->name('checklists.create');
    Route::post('/store/{type}', [ChecklistController::class, 'store'])->name('checklists.store');
    Route::get('/{checklist}', [ChecklistController::class, 'show'])->name('checklists.show');
    Route::get('/{checklist}/edit', [ChecklistController::class, 'edit'])->name('checklists.edit');
    Route::put('/{checklist}', [ChecklistController::class, 'update'])->name('checklists.update');
    Route::delete('/{checklist}', [ChecklistController::class, 'destroy'])->name('checklists.destroy');
    Route::get('/{checklist}/preview', [ChecklistController::class, 'preview'])->name('checklists.preview');
    Route::post('/{checklist}/confirm', [ChecklistController::class, 'confirm'])->name('checklists.confirm');
    Route::get('/checklists/{checklist}/approve', [ChecklistController::class, 'approve'])->name('checklists.approve');
    Route::get('/{checklist}/forward', [ChecklistController::class, 'forward'])->name('checklists.forward');
    Route::post('/{checklist}/send-forward', [ChecklistController::class, 'sendForward'])->name('checklists.send-forward');
    Route::post('/{checklist}/link-to-jcr', [ChecklistController::class, 'linkToJcr'])->name('checklists.link-to-jcr');
    // Internal route for sending to external signer
    Route::post('/checklists/{checklist}/send-external', [ChecklistController::class, 'sendToExternalSigner'])->name('checklists.send-external');

    // // Admin routes
    // Route::middleware('role:super-admin,head-logging-service')->group(function () {
    //     Route::get('/{checklist}/force-edit', [ChecklistController::class, 'forceEdit'])->name('checklists.force-edit');
    //     Route::put('/{checklist}/force-update', [ChecklistController::class, 'forceUpdate'])->name('checklists.force-update');
    // });

    // Notification routes
    Route::middleware('auth')->group(function () {
        Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
        Route::post('/notifications/mark-as-read', [NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');
        Route::post('/notifications/mark-all-as-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.markAllAsRead');
    });

    // External signer routes (public)
    Route::prefix('external-signer')->group(function () {
        Route::get('/{checklist}', [ExternalSignerController::class, 'show'])->name('external-signer.show');
        Route::post('/{checklist}', [ExternalSignerController::class, 'store'])->name('external-signer.store');
    });

});

require __DIR__ . '/auth.php';
