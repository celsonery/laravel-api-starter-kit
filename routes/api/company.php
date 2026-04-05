<?php

use Illuminate\Support\Facades\Route;

Route::get('/company', function () {
    return response()->json(['message' => 'Company Working'], 200);
});
