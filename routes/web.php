<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProjectController;

Route::get('/', function () {
    return view('welcome');
});

Route::resource('projects', ProjectController::class);
