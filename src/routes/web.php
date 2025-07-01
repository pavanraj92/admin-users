<?php

use Illuminate\Support\Facades\Route;
use admin\users\Controllers\UserManagerController;

Route::name('admin.')->middleware(['web','auth:admin'])->group(function () {  
    Route::middleware('auth:admin')->group(function () {
        Route::resource('users', UserManagerController::class);
        Route::post('users/updateStatus', [UserManagerController::class, 'updateStatus'])->name('users.updateStatus');
    });
});
