<?php

use App\Http\Controllers\Admin\LineSkillController as AdminLineSkillController;
use App\Http\Controllers\Admin\WeaponController as AdminWeaponController;
use App\Http\Controllers\Admin\WeaponLineController as AdminWeaponLineController;
use App\Http\Controllers\Admin\WeaponSkillController as AdminWeaponSkillController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\SkillMediaController;
use App\Http\Controllers\VerificationController;
use App\Http\Controllers\WikiController;
use App\Http\Controllers\WeaponController;
use App\Http\Controllers\WeaponLineController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return app(WikiController::class)->index();
});

Route::get('/wiki', [WikiController::class, 'index'])->name('wiki');

Route::get('/weapon-lines', [WeaponLineController::class, 'index'])->name('weapon-lines.index');
Route::get('/weapon-lines/{slug}', [WeaponLineController::class, 'show'])->name('weapon-lines.show');
Route::get('/weapons', [WeaponController::class, 'index'])->name('weapons.index');
Route::get('/weapons/{slug}', [WeaponController::class, 'show'])->name('weapons.show');
Route::get('/media/{media}', [SkillMediaController::class, 'show'])->middleware('signed')->name('media.show');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'create'])->name('login');
    Route::post('/login', [AuthController::class, 'store'])->name('login.store');

    Route::get('/register', [RegisterController::class, 'create'])->name('register');
    Route::post('/register', [RegisterController::class, 'store'])->name('register.store');

    Route::get('/email/verify', [VerificationController::class, 'show'])->name('verification.notice');
    Route::post('/email/verify', [VerificationController::class, 'store'])->name('verification.verify');
    Route::post('/email/resend', [VerificationController::class, 'resend'])->name('verification.resend');
});

Route::post('/logout', [AuthController::class, 'destroy'])->middleware('auth')->name('logout');

Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/weapon-lines', [AdminWeaponLineController::class, 'index'])->name('weapon-lines.index');
    Route::get('/weapon-lines/create', [AdminWeaponLineController::class, 'create'])->name('weapon-lines.create');
    Route::post('/weapon-lines', [AdminWeaponLineController::class, 'store'])->name('weapon-lines.store');
    Route::get('/weapon-lines/{id}/edit', [AdminWeaponLineController::class, 'edit'])->name('weapon-lines.edit');
    Route::put('/weapon-lines/{id}', [AdminWeaponLineController::class, 'update'])->name('weapon-lines.update');

    Route::get('/weapon-lines/{weaponLineId}/skills/create', [AdminLineSkillController::class, 'create'])->name('line-skills.create');
    Route::post('/weapon-lines/{weaponLineId}/skills', [AdminLineSkillController::class, 'store'])->name('line-skills.store');
    Route::get('/line-skills/{id}/edit', [AdminLineSkillController::class, 'edit'])->name('line-skills.edit');
    Route::put('/line-skills/{id}', [AdminLineSkillController::class, 'update'])->name('line-skills.update');

    Route::get('/weapons', [AdminWeaponController::class, 'index'])->name('weapons.index');
    Route::get('/weapons/create', [AdminWeaponController::class, 'create'])->name('weapons.create');
    Route::post('/weapons', [AdminWeaponController::class, 'store'])->name('weapons.store');
    Route::get('/weapons/{id}/edit', [AdminWeaponController::class, 'edit'])->name('weapons.edit');
    Route::put('/weapons/{id}', [AdminWeaponController::class, 'update'])->name('weapons.update');

    Route::get('/weapons/{weaponId}/skill', [AdminWeaponSkillController::class, 'edit'])->name('weapon-skills.edit');
    Route::put('/weapons/{weaponId}/skill', [AdminWeaponSkillController::class, 'update'])->name('weapon-skills.update');
});
