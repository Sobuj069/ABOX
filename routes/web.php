<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\VoiceController;
use App\Http\Controllers\AppIntegrationController;
use App\Http\Controllers\AudioRecordingController;
use App\Http\Controllers\VipController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\VoiceManagementController;
use App\Http\Controllers\Admin\AppManagementController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\Admin\RecordingManagementController;
use App\Http\Controllers\Admin\SettingController;

// Main App Interface
Route::get('/', [HomeController::class, 'index'])->name('home');

// Voice APIs
Route::get('/voices', [VoiceController::class, 'index'])->name('voices.index');
Route::get('/voice/{voice}', [VoiceController::class, 'show'])->name('voices.show');
Route::post('/voice/apply/{voice}', [VoiceController::class, 'apply'])->name('voice.apply');

// App Integration APIs
Route::get('/apps', [AppIntegrationController::class, 'index'])->name('apps.index');
Route::post('/app/toggle/{targetApp}', [AppIntegrationController::class, 'toggle'])->name('app.toggle');

// Audio Processing & Recordings
Route::post('/recording/upload', [AudioRecordingController::class, 'upload'])->name('recording.upload');
Route::delete('/recording/{recording}', [AudioRecordingController::class, 'destroy'])->name('recording.destroy');

// VIP & Credits
Route::get('/vip/plans', [VipController::class, 'plans'])->name('vip.plans');
Route::post('/vip/subscribe', [VipController::class, 'subscribe'])->name('vip.subscribe');
Route::post('/vip/credits', [VipController::class, 'addCredits'])->name('vip.credits');

// Authentication & Quick Switcher
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::post('/auth/switch', [AuthController::class, 'switchRole'])->name('auth.switch');

// Admin Panel
Route::prefix('admin')->middleware(['web', 'admin'])->name('admin.')->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
    
    Route::resource('voices', VoiceManagementController::class);
    Route::resource('apps', AppManagementController::class);
    
    Route::get('users', [UserManagementController::class, 'index'])->name('users.index');
    Route::put('users/{user}', [UserManagementController::class, 'update'])->name('users.update');
    Route::delete('users/{user}', [UserManagementController::class, 'destroy'])->name('users.destroy');
    
    Route::get('recordings', [RecordingManagementController::class, 'index'])->name('recordings.index');
    Route::delete('recordings/{recording}', [RecordingManagementController::class, 'destroy'])->name('recordings.destroy');
    
    Route::get('settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('settings', [SettingController::class, 'update'])->name('settings.update');
});
