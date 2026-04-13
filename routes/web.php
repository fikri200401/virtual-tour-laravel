<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\VirtualTourController;
use App\Http\Controllers\FasilitasController;
use App\Http\Controllers\TentangController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\KritikSaranController;
use App\Http\Middleware\AdminAuth;
use App\Http\Middleware\TrackVisitor;

// Public routes with visitor tracking
Route::middleware(TrackVisitor::class)->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/virtual-tour', [VirtualTourController::class, 'index'])->name('virtual-tour');
    Route::get('/fasilitas', [FasilitasController::class, 'index'])->name('fasilitas');
    Route::get('/tentang', [TentangController::class, 'index'])->name('tentang');
});

// Kritik & Saran
Route::post('/kritik-saran', [KritikSaranController::class, 'store'])->name('kritik-saran.store');

// Auth routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.process');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

// Admin routes
Route::middleware(AdminAuth::class)->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

    Route::post('/content/update', [AdminController::class, 'updateContent'])->name('admin.content.update');

    Route::post('/facility/add', [AdminController::class, 'addFacility'])->name('admin.facility.add');
    Route::post('/facility/update', [AdminController::class, 'updateFacility'])->name('admin.facility.update');
    Route::post('/facility/delete', [AdminController::class, 'deleteFacility'])->name('admin.facility.delete');

    Route::post('/user/add', [AdminController::class, 'addUser'])->name('admin.user.add');
    Route::post('/user/update', [AdminController::class, 'updateUser'])->name('admin.user.update');
    Route::post('/user/delete', [AdminController::class, 'deleteUser'])->name('admin.user.delete');

    Route::post('/scene/add', [AdminController::class, 'addScene'])->name('admin.scene.add');
    Route::post('/scene/update', [AdminController::class, 'updateScene'])->name('admin.scene.update');
    Route::post('/scene/delete', [AdminController::class, 'deleteScene'])->name('admin.scene.delete');

    Route::post('/hotspot/add', [AdminController::class, 'addHotspot'])->name('admin.hotspot.add');
    Route::post('/hotspot/delete', [AdminController::class, 'deleteHotspot'])->name('admin.hotspot.delete');

    Route::post('/kritik-saran/delete', [AdminController::class, 'deleteKritikSaran'])->name('admin.kritik.delete');

    Route::post('/upload', [AdminController::class, 'uploadImage'])->name('admin.upload');
    Route::post('/image/delete', [AdminController::class, 'deleteImage'])->name('admin.image.delete');
});
