<?php

namespace App\Http\Controllers\Blocked_Days;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\BlockedDayRequests\StoreBlockedDayRequest;
use App\Http\Requests\BlockedDayRequests\UpdateBlockedDayRequest;
use App\Models\BlockedDay;

class BlockedDayController extends Controller
{
    public function create()
    {

        return view('blocked_days.create');
    }

    public function store(StoreBlockedDayRequest $request)
    {
        $validated = $request->validated();
        $blocked_day = BlockedDay::create([
            'name' => $validated['name'],
            'from' => $validated['from'],
            'to' => $validated['to'],
        ]);
        return redirect()->route('blocked-days.index');
    }

    public function index()
    {
        $blocked_days = BlockedDay::search(request(['search']))
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        $helper = new Helper();
        $blocked_dayDates = $helper->getBlockedDays();

        return view('blocked-days.index', [
            'blocked_days' => $blocked_days,
            'blocked_day_dates' => $blocked_dayDates
        ]);
    }

    public function show(BlockedDay $blocked_day)
    {
        return view('blocked_days.show', [
            'blocked_day' => $blocked_day,
        ]);
    }

    public function update(UpdateBlockedDayRequest $request, BlockedDay $blocked_day)
    {
        $validated = $request->validated();
        $blocked_day->update([
            'name' => $validated['name'],
            'from' => $validated['from'],
            'to' => $validated['to'],
        ]);
        return redirect()->route('blocked-days.index');
    }

    public function destroy(BlockedDay $blocked_day)
    {
        $blocked_day->delete();
        return redirect()->route('blocked-days.index');
    }
}
