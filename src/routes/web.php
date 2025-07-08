<?php

use Illuminate\Support\Facades\Route;
use admin\users\Controllers\UserManagerController;

Route::name('admin.')->middleware(['web','auth:admin'])->group(function () {  
    Route::prefix('users/{type}')->name('users.')->group(function () {
        Route::resource('', UserManagerController::class)->parameters([
            '' => 'user',
        ]);
        Route::post('updateStatus', [UserManagerController::class, 'updateStatus'])->name('updateStatus');
    });

});
