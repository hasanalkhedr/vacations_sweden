<x-sidebar>
    @section('title', __('Calendar'))
    <div class="w-full bg-white flex justify-between items-center blue-color">
        <div class="p-4 text-lg">
            {{ $month_name }} {{ $year }}
        </div>
        <div class="px-6 py-3 text-xl font-bold text-black">
            <a href="{{ url(route('leaves.getCalendarForm')) }}">
                <button
                    class="text-white border hover:bg-blue-400 focus:ring-2 focus:outline-none focus:ring-gray-100 font-medium rounded-lg text-sm px-5 py-2.5 text-center mb-2 blue-bg">
                    {{ __('Generate New Calendar') }}
                </button>
            </a>
        </div>
    </div>
    <div class="my-2 mx-4 grid grid-cols-3 gap-4">
        <div class="bg-gray-500 text-white p-2 text-center rounded">{{ __('Weekend') }}</div>
        <div class="bg-green-600 text-white p-2 text-center rounded">{{ __('National Holiday') }}</div>
        <div class="text-white p-2 text-center rounded" style="background: #6B9ADA;">{{ __('Approved') }}</div>
        <div class="text-white p-2 text-center rounded" style="background: #e0a614;">{{ __('Pending') }}</div>
    </div>
    <div class="overflow-x-auto relative shadow-md sm:rounded-lg">
        <table class="mx-4 my-4 w-full text-sm text-left text-gray-500" style="display: table-caption;">
            <thead class="text-s text-gray-700 uppercase bg-gray-50">
                <tr>
                    <th class="border"></th>
                    @foreach ($dates as $date)
                        <th scope="col" class="text-center border py-3 blue-color">
                            {{ $date->day }}
                        </th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach ($employees as $employee)
                    <tr class="bg-white hover:bg-gray-50">
                        <td class="border-2 px-2 py-2 sm:text-sm font-bold whitespace-nowrap blue-color">
                            <div>
                                {{ $employee->first_name }} {{ $employee->last_name }}
                            </div>
                        </td>
                        @foreach ($dates as $date)
                            @if (in_array($date->format('N'), $employee->weekdays_off))
                                <td
                                    class="border border-b py-1 2xl:px-3 xl:px-3 text-gray-900 bg-gray-500 whitespace-nowrap">
                                </td>
                            @elseif(in_array($date->format('Y-m-d'), $holidays))
                                <td
                                    class="border border-b py-1 2xl:px-3 xl:px-3 text-gray-900 bg-green-600 whitespace-nowrap">
                                </td>
                            @elseif(array_key_exists($employee->id . '&' . $date->format('Y-m-d'), $leaveId_dates_pairs))
                                @if ($leaveId_dates_pairs[$employee->id . '&' . $date->format('Y-m-d')]->employee->id == $employee->id)
                                    @if ($leaveId_dates_pairs[$employee->id . '&' . $date->format('Y-m-d')]->leave_status == 1)
                                        @if ($leaveId_dates_pairs[$employee->id . '&' . $date->format('Y-m-d')]->leave_duration->name == 'Half Day AM')
                                            <td class="border py-1 2xl:px-3 xl:px-3 text-gray-900 whitespace-nowrap"
                                                style="clip-path: inset(3%); background: linear-gradient(135deg, #6B9ADA 50%,#ffffff 50%); text-align: center;">
                                                {{ $leaveId_dates_pairs[$employee->id . '&' . $date->format('Y-m-d')]->leave_type->abbreviation }}
                                            </td>
                                        @elseif($leaveId_dates_pairs[$employee->id . '&' . $date->format('Y-m-d')]->leave_duration->name == 'Half Day PM')
                                            <td class="border py-1 2xl:px-3 xl:px-3 text-gray-900 whitespace-nowrap"
                                                style="clip-path: inset(3%); background: linear-gradient(135deg, #ffffff 50%,#6B9ADA 50%); text-align: center;">
                                                {{ $leaveId_dates_pairs[$employee->id . '&' . $date->format('Y-m-d')]->leave_type->abbreviation }}
                                            </td>
                                        @else
                                            <td class="border py-1 2xl:px-3 xl:px-3 text-gray-900 whitespace-nowrap"
                                                style="background: #6B9ADA; text-align: center;">
                                                {{ $leaveId_dates_pairs[$employee->id . '&' . $date->format('Y-m-d')]->leave_type->abbreviation }}
                                            </td>
                                        @endif
                                    @else
                                        @if ($leaveId_dates_pairs[$employee->id . '&' . $date->format('Y-m-d')]->leave_duration->name == 'Half Day AM')
                                            <td class="border py-1 2xl:px-3 xl:px-3 text-gray-900 whitespace-nowrap"
                                                style="clip-path: inset(3%); background: linear-gradient(135deg, #e0a614 50%,#ffffff 50%); text-align: center;">
                                                {{ $leaveId_dates_pairs[$employee->id . '&' . $date->format('Y-m-d')]->leave_type->abbreviation }}
                                            </td>
                                        @elseif($leaveId_dates_pairs[$employee->id . '&' . $date->format('Y-m-d')]->leave_duration->name == 'Half Day PM')
                                            <td class="border py-1 2xl:px-3 xl:px-3 text-gray-900 whitespace-nowrap"
                                                style="clip-path: inset(3%); background: linear-gradient(135deg, #ffffff 50%,#e0a614 50%); text-align: center;">
                                                {{ $leaveId_dates_pairs[$employee->id . '&' . $date->format('Y-m-d')]->leave_type->abbreviation }}
                                            </td>
                                        @else
                                            <td class="border py-1 2xl:px-3 xl:px-3 text-gray-900 whitespace-nowrap"
                                                style="background: #e0a614; text-align: center;">
                                                {{ $leaveId_dates_pairs[$employee->id . '&' . $date->format('Y-m-d')]->leave_type->abbreviation }}
                                            </td>
                                        @endif
                                    @endif
                                @else
                                    <td class="border py-1 2xl:px-3 xl:px-3 text-gray-900 whitespace-nowrap">
                                    </td>
                                @endif
                            @else
                                <td class="border py-1 2xl:px-3 xl:px-3 text-gray-900 whitespace-nowrap">
                                </td>
                            @endif
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-sidebar>
