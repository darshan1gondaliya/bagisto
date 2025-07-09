<?php

use Illuminate\Support\Facades\Route;
use Wontonee\Delhivery\Http\Controllers\Admin\DelhiveryController;

Route::group([
    'middleware' => ['web', 'admin'],
    'prefix' => config('app.admin_path', 'admin')
], function () {
    Route::get('/delhivery', [DelhiveryController::class, 'index'])
         ->name('admin.delhivery.index');
         
    Route::post('/delhivery/save', [DelhiveryController::class, 'store'])
         ->name('admin.delhivery.store');
});