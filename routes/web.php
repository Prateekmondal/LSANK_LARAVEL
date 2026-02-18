<?php

use App\Http\Controllers\AjaxCallController;
use App\Http\Controllers\ExternalSignerController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\JcrController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ChecklistController;
use App\Http\Controllers\TimeRegisterController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

if (app()->environment('local')) {
    // Preview error pages locally: visit /_errors/404, /_errors/500 etc.
    Route::get('/_errors/{code}', function ($code) {
        abort(intval($code));
    });
}

// Route::get('/dashboard', function () {
// $user = Auth::user();
//     $jcrs = Auth::user()->jcrs()->with(['users', 'logs', 'explosives'])->orderBy('arrivalOffice_date', 'desc')
//             ->orderBy('arrivalOffice_time', 'desc')->paginate(10);
//     return view('dashboard', ['jcrs'=>$jcrs, 'user'=>$user]);
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    // Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile/update_avatar', [ProfileController::class, 'update_avatar'])->name('profile.update_avatar');
    Route::delete('/profile/delete', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// JCR Routes - Protected by auth middleware
Route::middleware(['auth'])->group(function () {

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

    // SAP Push Route (requires Technical_Support_Group role)
    Route::post('jcr/{jcr}/push-to-sap', [JcrController::class, 'pushToSap'])
        ->name('jcr.push-to-sap')
        ->middleware('can:push_jcr_to_sap');

    Route::get('/dashboard', [JcrController::class, 'dashboardView'])->name('dashboard');
    Route::get('jcr/download', [JcrController::class, 'download'])->name('jcr.download');
    Route::get('/jcr/{jcr}/print', [JcrController::class, 'print'])->name('jcr.print');


    Route::get('/dashboard/jobs', [JcrController::class, 'dashboardJobs'])->name('dashboard.jobs');
    Route::get('/dashboard/jobs/export', [JcrController::class, 'exportJobs'])->name('dashboard.jobs.export');

    // Link time register to JCR
    Route::post('/jcr/{jcr}/link-time-register', [JcrController::class, 'linkTimeRegister'])
        ->name('jcr.link-time-register');
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
    Route::get('/{checklist}/edit/{type}', [ChecklistController::class, 'edit'])->name('checklists.edit');
    Route::put('/{checklist}', [ChecklistController::class, 'update'])->name('checklists.update');
    Route::delete('/{checklist}', [ChecklistController::class, 'destroy'])->name('checklists.destroy');
    Route::get('/{checklist}/preview', [ChecklistController::class, 'preview'])->name('checklists.preview');
    Route::post('/{checklist}/confirm', [ChecklistController::class, 'confirm'])->name('checklists.confirm');
    Route::get('/checklists/{checklist}/approve', [ChecklistController::class, 'approve'])->name('checklists.approve');
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
        Route::get('notifications/{notification}/read', [NotificationController::class, 'read'])->name('notifications.read');
    });

    
});
// External signer routes (public)
Route::prefix('external-signer')->group(function () {
    Route::get('/{checklist}', [ExternalSignerController::class, 'show'])->name('external-signer.show');
    Route::post('/{checklist}', [ExternalSignerController::class, 'store'])->name('external-signer.store');
});
// Time register Rig signature routes
Route::get('/rig-signature/{token}', [TimeRegisterController::class, 'rigSignatureForm'])
    ->name('time-registers.rig-signature');
    
Route::post('/rig-signature/{token}', [TimeRegisterController::class, 'storeRigSignature'])
    ->name('time-registers.store-rig-signature');

Route::middleware(['auth'])->group(function () {
    // AJAX routes for time register details
    Route::get('/ajax/time-register/{id}/details', [JcrController::class, 'getTimeRegisterDetails'])
        ->name('ajax.time-register.details');
        
    Route::resource('time-registers', TimeRegisterController::class);
    
    // Preview and final submission
    Route::get('/time-registers/{timeRegister}/preview', [TimeRegisterController::class, 'preview'])
        ->name('time-registers.preview');
        
    Route::post('/time-registers/{timeRegister}/final-submit', [TimeRegisterController::class, 'finalSubmit'])
        ->name('time-registers.final-submit');
    
    // Resend signature request email to rig representative
    Route::post('/time-registers/{timeRegister}/resend', [TimeRegisterController::class, 'resend'])
        ->name('time-registers.resend');
    
});

require __DIR__ . '/auth.php';
