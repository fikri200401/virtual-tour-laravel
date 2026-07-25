<?php

use App\Http\Controllers\AdminContentController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminFacilityController;
use App\Http\Controllers\AdminFeedbackController;
use App\Http\Controllers\AdminMediaController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FasilitasController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\KritikSaranController;
use App\Http\Controllers\TentangController;
use App\Http\Controllers\VirtualTourController;
use App\Http\Middleware\AdminAuth;
use App\Http\Middleware\TrackVisitor;
use Illuminate\Support\Facades\Route;

Route::middleware(TrackVisitor::class)->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/virtual-tour', [VirtualTourController::class, 'index'])->name('virtual-tour');
    Route::get('/fasilitas', [FasilitasController::class, 'index'])->name('fasilitas');
    Route::get('/tentang', [TentangController::class, 'index'])->name('tentang');
});

Route::get('/kritik-saran/captcha', [KritikSaranController::class, 'captcha'])
    ->middleware('throttle:30,1')
    ->name('kritik-saran.captcha');
Route::post('/kritik-saran', [KritikSaranController::class, 'store'])
    ->middleware('throttle:10,1')
    ->name('kritik-saran.store');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.process');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(AdminAuth::class)->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

    Route::post('/content/update-section', [AdminContentController::class, 'updateSection'])->name('admin.content.section.update');

    Route::post('/facility/add', [AdminFacilityController::class, 'store'])->name('admin.facility.add');
    Route::post('/facility/update', [AdminFacilityController::class, 'update'])->name('admin.facility.update');
    Route::post('/facility/delete', [AdminFacilityController::class, 'destroy'])->name('admin.facility.delete');
    Route::post('/facility/tour/upload', [AdminFacilityController::class, 'uploadTour'])->name('admin.facility.tour.upload');
    Route::post('/facility/tour/delete', [AdminFacilityController::class, 'deleteTour'])->name('admin.facility.tour.delete');

    Route::post('/user/add', [AdminUserController::class, 'store'])->name('admin.user.add');
    Route::post('/user/update', [AdminUserController::class, 'update'])->name('admin.user.update');
    Route::post('/user/delete', [AdminUserController::class, 'destroy'])->name('admin.user.delete');

    Route::post('/kritik-saran/delete', [AdminFeedbackController::class, 'destroy'])->name('admin.kritik.delete');
    Route::post('/kritik-saran/telegram', [AdminFeedbackController::class, 'updateTelegram'])->name('admin.kritik.telegram.update');

    Route::post('/upload', [AdminMediaController::class, 'uploadImage'])->name('admin.upload');
    Route::post('/image/delete', [AdminMediaController::class, 'deleteImage'])->name('admin.image.delete');

    Route::post('/tour/upload', [AdminMediaController::class, 'uploadTour'])->name('admin.tour.upload');
    Route::post('/tour/delete', [AdminMediaController::class, 'deleteTour'])->name('admin.tour.delete');
});
