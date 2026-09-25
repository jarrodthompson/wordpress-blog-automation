<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EditorController;
use App\Http\Controllers\ModuleController;
use App\Http\Controllers\SyncController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

Route::post('/sync', [SyncController::class, 'sync'])->name('sync');

// Blog production
Route::get('/blog-manager', [ModuleController::class, 'blogManager'])->name('blog-manager');
Route::get('/editor', [EditorController::class, 'index'])->name('editor');
Route::post('/editor/generate', [EditorController::class, 'generate'])->name('editor.generate');
Route::post('/editor/save', [EditorController::class, 'save'])->name('editor.save');
Route::get('/ai-image', [ModuleController::class, 'aiImage'])->name('ai-image');

// Distribution
Route::get('/crm', [ModuleController::class, 'crm'])->name('crm');
Route::get('/scoreboard', [ModuleController::class, 'scoreboard'])->name('scoreboard');
Route::get('/products', [ModuleController::class, 'products'])->name('products');
Route::get('/autoblog', [ModuleController::class, 'autoblog'])->name('autoblog');

// Administration
Route::get('/activity', [ModuleController::class, 'activity'])->name('activity');
Route::get('/features', [ModuleController::class, 'features'])->name('features');
Route::get('/brand-dna', [ModuleController::class, 'brandDna'])->name('brand-dna');
Route::get('/settings', [ModuleController::class, 'settings'])->name('settings');
