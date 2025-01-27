<?php

namespace App\Http\Controllers\Holidays;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\HolidayRequests\StoreHolidayRequest;
use App\Http\Requests\HolidayRequests\UpdateHolidayRequest;
use App\Models\Holiday;
use Illuminate\Http\Request;

class HolidayController extends Controller
{
    public function create()
    {

        return view('holidays.create');
    }

    public function store(StoreHolidayRequest $request)
    {
        $validated = $request->validated();
        $holiday = Holiday::create([
            'name' => $validated['name'],
            'from' => $validated['from'],
            'to' => $validated['to'],
        ]);
        return redirect()->route('holidays.index');
    }

    public function index()
    {
        $holidays = Holiday::search(request(['search']))
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        $helper = new Helper();
        $holidayDates = $helper->getHolidays();

        return view('holidays.index', [
            'holidays' => $holidays,
            'holiday_dates' => $holidayDates
        ]);
    }

    public function show(Holiday $holiday)
    {
        return view('holidays.show', [
            'holiday' => $holiday,
        ]);
    }

    public function update(UpdateHolidayRequest $request, Holiday $holiday)
    {
        $validated = $request->validated();
        $holiday->update([
            'name' => $validated['name'],
            'from' => $validated['from'],
            'to' => $validated['to'],
        ]);
        return redirect()->route('holidays.index');
    }

    public function destroy(Holiday $holiday)
    {
        $holiday->delete();
        return redirect()->route('holidays.index');
    }
}
