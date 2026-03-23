<?php

use Illuminate\Support\Facades\Route;

Route::middleware('api.key')->group(function () {
    Route::get('/approved-ot', [\App\Http\Controllers\Api\ApprovedOtController::class, 'index']);
    Route::get('/approved-leaves', [\App\Http\Controllers\Api\ApprovedLeavesController::class, 'index']);
});
