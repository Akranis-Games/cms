<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HolidayController;

Route::get('/holidays/active', [HolidayController::class, 'getActiveHolidays']);

