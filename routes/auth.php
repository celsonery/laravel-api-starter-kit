<?php

use Illuminate\Support\Facades\Route;

Route::get('/auth', function () {
    return response()->json(['message' => 'auth working'], 200);
});
