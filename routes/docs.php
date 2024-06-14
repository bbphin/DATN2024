<?php
use Illuminate\Support\Facades\Route;
use Dedoc\Scramble\Http\Controllers\OpenApiController;
Route::get('docs', function () {
    return view('scramble::redoc');
});

