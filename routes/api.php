<?php
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::get('health', function () {
        return response()->json(['message' => 'Api running']);
    });

    require __DIR__.'/auth.php';
    require __DIR__.'/api/company.php';
});
