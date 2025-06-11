<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
use App\Http\Controllers\ProfileController;

Route::get('/municipality/{citymunCode}', [ProfileController::class, 'showMunicipality']);