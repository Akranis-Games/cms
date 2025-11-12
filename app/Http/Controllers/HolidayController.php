<?php

namespace App\Http\Controllers;

use App\Models\HolidaySetting;
use Illuminate\Http\Request;
use Carbon\Carbon;

class HolidayController extends Controller
{
    public function getActiveHolidays()
    {
        $today = Carbon::today();
        
        $holidays = HolidaySetting::where('is_active', true)
            ->where('date_start', '<=', $today)
            ->where('date_end', '>=', $today)
            ->get();

        return response()->json($holidays);
    }
}

