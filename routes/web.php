<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TenagaKerjaWizardController;
use App\Http\Controllers\UserTenagaKerjaController;
use App\Http\Controllers\TenagaKerjaController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\TenagaKerjaVerifController;
use App\Http\Controllers\Admin\UserController;


// PUBLIC
Route::get('/', [HomeController::class, 'index'])->name('home');

Route::middleware('guest')->group(function () {
    Route::get('/login',     [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login',    [LoginController::class, 'login']);
    Route::get('/register',  [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});

Route::post('/logout', [LoginController::class, 'logout'])
    ->name('logout')->middleware('auth');

// PROFILE
Route::middleware('auth')->group(function () {
    Route::get('/profile',          [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/settings', [ProfileController::class, 'settings'])->name('profile.settings');
    Route::post('/profile/settings', [ProfileController::class, 'updateSettings'])->name('profile.settings.update');
    Route::post('/profile/avatar',  [ProfileController::class, 'updateAvatar'])->name('profile.avatar.update');
});

// USER
Route::middleware(['auth', 'role:user'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Riwayat kuesioner
    Route::prefix('kuesioner-tenaga-kerja')->name('tenagakerja.')->group(function () {
        Route::get('/',              [UserTenagaKerjaController::class, 'index'])->name('index');
        Route::get('/{id}',         [UserTenagaKerjaController::class, 'show'])->name('show');
        Route::get('/{id}/edit',    [TenagaKerjaController::class, 'edit'])->name('edit');
        Route::put('/{id}',         [TenagaKerjaController::class, 'update'])->name('update');
        Route::get('/{id}/export',  [UserTenagaKerjaController::class, 'exportExcel'])->name('export'); // ← ini
    });

    // Wizard
    Route::prefix('tenagakerja')->name('tkw.')->group(function () {
        Route::get('step-1',  [TenagaKerjaWizardController::class, 'showStep1'])->name('step1');
        Route::post('step-1', [TenagaKerjaWizardController::class, 'postStep1']);
        Route::get('step-2',  [TenagaKerjaWizardController::class, 'showStep2'])->name('step2');
        Route::post('step-2', [TenagaKerjaWizardController::class, 'postStep2']);
        Route::get('step-3',  [TenagaKerjaWizardController::class, 'showStep3'])->name('step3');
        Route::post('step-3', [TenagaKerjaWizardController::class, 'postStep3']);
        Route::get('step-4',  [TenagaKerjaWizardController::class, 'showStep4'])->name('step4');
        Route::post('step-4', [TenagaKerjaWizardController::class, 'postStep4']);
    });
});

// ADMIN
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function () {

    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // Verifikasi tenaga kerja
    Route::prefix('tenagakerja')->name('tkw.')->group(function () {
        Route::get('/',             [TenagaKerjaVerifController::class, 'index'])->name('index');
        Route::get('/list-rt',      [TenagaKerjaVerifController::class, 'listRtPage'])->name('listrt');
        Route::get('/rt/{rt}',      [TenagaKerjaVerifController::class, 'showRtData'])->name('showrt')->where('rt', '[0-9]+');
        Route::get('/{id}',         [TenagaKerjaVerifController::class, 'show'])->name('show');
        Route::get('/{id}/export',  [TenagaKerjaVerifController::class, 'exportExcel'])->name('export'); // ← ini
        Route::post('/{id}/process', [TenagaKerjaVerifController::class, 'processVerification'])->name('process');
        Route::delete('/{id}',      [TenagaKerjaVerifController::class, 'destroy'])->name('destroy');
    });

    // Manajemen user
    Route::prefix('user')->name('user.')->group(function () {
        Route::get('/',           [UserController::class, 'index'])->name('index');
        Route::get('/create',     [UserController::class, 'create'])->name('create');
        Route::post('/',          [UserController::class, 'store'])->name('store');
        Route::get('/{id}/edit',  [UserController::class, 'edit'])->name('edit');
        Route::put('/{id}',       [UserController::class, 'update'])->name('update');
        Route::delete('/{id}',    [UserController::class, 'destroy'])->name('destroy');
        Route::get('/{id}/export', [UserTenagaKerjaController::class, 'exportExcel'])
            ->name('tenagakerja.export');
    });
});
