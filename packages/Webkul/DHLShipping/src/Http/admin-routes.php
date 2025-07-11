<?php

use Illuminate\Support\Facades\Route;
use Webkul\DHLShipping\Http\Controllers\Admin\DHLController;

Route::group([
    'middleware' => ['web', 'admin'],
    'prefix' => config('app.admin_path', 'admin')
], function () {
    Route::get('/dhl/test', [DHLController::class, 'testConnection'])
         ->name('admin.dhl.test');
});