<?php

use App\Http\Controllers\Admin\LineSkillController as AdminLineSkillController;
use App\Http\Controllers\Admin\ArmorController as AdminArmorController;
use App\Http\Controllers\Admin\WeaponController as AdminWeaponController;
use App\Http\Controllers\Admin\WeaponLineController as AdminWeaponLineController;
use App\Http\Controllers\Admin\WeaponSkillController as AdminWeaponSkillController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\SkillMediaController;
use App\Http\Controllers\WikiController;
use App\Http\Controllers\WeaponController;
use App\Http\Controllers\WeaponLineController;
use App\Http\Controllers\ArmorController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $user = auth()->user();

    if ($user?->isAdmin()) {
        return redirect()->route('admin.dashboard');
    }

    return app(WikiController::class)->index();
})->middleware('auth');

Route::middleware('auth')->group(function () {
    Route::get('/wiki', [WikiController::class, 'index'])->name('wiki');

    Route::get('/weapon-lines', [WeaponLineController::class, 'index'])->name('weapon-lines.index');
    Route::get('/weapon-lines/{slug}', [WeaponLineController::class, 'show'])->name('weapon-lines.show');
    Route::get('/weapons', [WeaponController::class, 'index'])->name('weapons.index');
    Route::get('/weapons/{slug}', [WeaponController::class, 'show'])->name('weapons.show');
    Route::get('/armor', [ArmorController::class, 'index'])->name('armor.index');
    Route::get('/armor/{slug}', [ArmorController::class, 'show'])->name('armor.show');
    Route::get('/media/{media}', [SkillMediaController::class, 'show'])->middleware('signed')->name('media.show');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'create'])->name('login');
    Route::post('/login', [AuthController::class, 'store'])
        ->middleware('throttle:login')
        ->name('login.store');

    Route::get('/register', [RegisterController::class, 'create'])->name('register');
    Route::post('/register', [RegisterController::class, 'store'])
        ->middleware('throttle:register')
        ->name('register.store');
});

Route::post('/logout', [AuthController::class, 'destroy'])->middleware('auth')->name('logout');

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', fn () => redirect()->route('admin.weapon-lines.index'))->name('dashboard');

    Route::get('/weapon-lines', [AdminWeaponLineController::class, 'index'])->name('weapon-lines.index');
    Route::get('/weapon-lines/create', [AdminWeaponLineController::class, 'create'])->name('weapon-lines.create');
    Route::post('/weapon-lines', [AdminWeaponLineController::class, 'store'])->name('weapon-lines.store');
    Route::get('/weapon-lines/{id}/edit', [AdminWeaponLineController::class, 'edit'])->name('weapon-lines.edit');
    Route::put('/weapon-lines/{id}', [AdminWeaponLineController::class, 'update'])->name('weapon-lines.update');
    Route::delete('/weapon-lines/{id}', [AdminWeaponLineController::class, 'destroy'])->name('weapon-lines.destroy');

    Route::get('/weapon-lines/{weaponLineId}/skills/create', [AdminLineSkillController::class, 'create'])->name('line-skills.create');
    Route::post('/weapon-lines/{weaponLineId}/skills', [AdminLineSkillController::class, 'store'])->name('line-skills.store');
    Route::get('/line-skills/{id}/edit', [AdminLineSkillController::class, 'edit'])->name('line-skills.edit');
    Route::put('/line-skills/{id}', [AdminLineSkillController::class, 'update'])->name('line-skills.update');

    Route::get('/weapons', [AdminWeaponController::class, 'index'])->name('weapons.index');
    Route::get('/weapons/create', [AdminWeaponController::class, 'create'])->name('weapons.create');
    Route::post('/weapons', [AdminWeaponController::class, 'store'])->name('weapons.store');
    Route::get('/weapons/{id}/edit', [AdminWeaponController::class, 'edit'])->name('weapons.edit');
    Route::put('/weapons/{id}', [AdminWeaponController::class, 'update'])->name('weapons.update');
    Route::delete('/weapons/{id}', [AdminWeaponController::class, 'destroy'])->name('weapons.destroy');

    Route::get('/weapons/{weaponId}/skill', [AdminWeaponSkillController::class, 'edit'])->name('weapon-skills.edit');
    Route::put('/weapons/{weaponId}/skill', [AdminWeaponSkillController::class, 'update'])->name('weapon-skills.update');

    Route::get('/armor', [AdminArmorController::class, 'index'])->name('armor.index');
    Route::get('/armor/create', [AdminArmorController::class, 'create'])->name('armor.create');
    Route::post('/armor', [AdminArmorController::class, 'store'])->name('armor.store');
    Route::get('/armor/{id}/edit', [AdminArmorController::class, 'edit'])->name('armor.edit');
    Route::put('/armor/{id}', [AdminArmorController::class, 'update'])->name('armor.update');
    Route::delete('/armor/{id}', [AdminArmorController::class, 'destroy'])->name('armor.destroy');
});
