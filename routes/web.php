<?php

use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\GoogleLoginController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EventAttachmentController;
use App\Http\Controllers\EventCreatorController;
use App\Http\Controllers\EventDetailsController;
use App\Http\Controllers\EventParticipantController;
use App\Http\Controllers\EventsController;
use App\Http\Middleware\CheckFilePermission;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

Route::middleware('guest')->group(function () {
    Route::get('/', [LoginController::class, 'index'])->name('login.index');
    Route::get('/register', [RegisterController::class, 'index'])->name('register.index');
    Route::get('/forgot-password', [ForgotPasswordController::class, 'index'])->name('forgot-password.index');
    Route::get('/verify-email', [VerifyEmailController::class, 'index'])->name('verify-email.index');
    Route::get('/reset-password', [ResetPasswordController::class, 'index'])->name('reset-password.index');

    Route::permanentRedirect('/login', '/');

    Route::middleware('throttle:10,1')->group(function () {
        Route::post('/login', [LoginController::class, 'store'])->name('login.store');
        Route::post('/register', [RegisterController::class, 'store'])->name('register.store');
        Route::post('/forgot-password', [ForgotPasswordController::class, 'store'])->name('forgot-password.store');
        Route::post('/resend-verification', [VerifyEmailController::class, 'store'])->name('verification.store');
        Route::post('/reset-password', [ResetPasswordController::class, 'store'])->name('reset-password.store');

        Route::post('/login/google', [GoogleLoginController::class, 'store'])->name('login.google.store');
    });
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
    Route::get('/create-event', [EventCreatorController::class, 'index'])->name('create-event.index');
    Route::get('/event/{id}', [EventDetailsController::class, 'index'])->name('event.index');
    Route::get('/events', [EventsController::class, 'index'])->name('events.index');
    Route::get('/events-list', [EventsController::class, 'list'])->name('events.list');

    Route::post('/create-event', [EventCreatorController::class, 'store'])->name('create-event.store');
    Route::put('/event/{id}', [EventDetailsController::class, 'update'])->name('event.update');
    Route::post('/event/join', [EventParticipantController::class, 'join'])->name('event.join');
    Route::post('/logout', function () {
        Auth::logout();

        return redirect()->route('login.index');
    })->name('logout.store');

    Route::post('/attachments', [EventAttachmentController::class, 'store'])->name('attachments.store');
    Route::delete('/attachments/{attachment}', [EventAttachmentController::class, 'destroy'])->name('attachments.destroy');

    Route::get('/storage/event-attachments/{fileName}', function ($fileName) {
        $path = Storage::disk('public')->path("event-attachments/$fileName");
        if (! Storage::disk('public')->exists("event-attachments/$fileName")) {
            abort(404);
        }

        return response()->file($path);
    })->middleware(CheckFilePermission::class)->name('attachments.show');
});

// not found page
Route::get('/{any}', function () {
    return inertia('NotFound');
})->where('any', '.*');
