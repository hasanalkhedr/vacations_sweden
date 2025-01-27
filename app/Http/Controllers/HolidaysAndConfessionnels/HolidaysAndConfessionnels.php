<?php

namespace App\Http\Controllers\HolidaysAndConfessionnels;

use App\Http\Controllers\Controller;
use App\Models\Holiday;
use Illuminate\Http\Request;

class HolidaysAndConfessionnels extends Controller
{
    public function index(Request $request) {

        $holidaysPage = $request->query('holidays_page', 1);
        $activeTab = $request->query('active_tab', 'holidays');

        $holidays = Holiday::paginate(10, ['*'], 'holidays_page');

        $holidays->appends(['active_tab' => $activeTab]);

        return view('holidays-and-confessionnels.index', [
            'holidays' => $holidays,
            'activeTab' => $activeTab
        ]);
    }
}
