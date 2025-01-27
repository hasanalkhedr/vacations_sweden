<x-sidebar>
    @section('title', __('Leave Report'))
    <div class="overflow-x-auto relative shadow-md">
        <div class="m-4">
            <div class="text-lg">
                <span class="font-bold blue-color">{{ __('Name') }}:</span> {{ $employee->first_name }}
                {{ $employee->last_name }}
            </div>
            <div class="text-lg">
                <span class="font-bold blue-color">{{ __('Department') }}:</span>
                {{ ucfirst(strtolower($employee->department->name)) }}
            </div>
            <div class="text-lg">
                <span class="font-bold blue-color">{{ __('Remaining Balances') }}:</span> {{ $employee->nb_of_days }}
                {{ __('Remaining Leaves') }}
            </div>
        </div>
        @unless ($leaves->isEmpty())
            <div class="m-4">
                <div class="text-md font-bold">
                    {{ __('Leave Types Count') }}:
                </div>
                @foreach ($data as $key => $single_data)
                    @if ($single_data['number_of_days_off'] > 0)
                        <div class="text-md">
                            {{ $single_data['number_of_days_off'] }} {{ __($key) }}
                        </div>
                    @endif
                @endforeach
            </div>
        @endunless
        <table class="w-full text-sm text-left text-gray-500">
            @unless ($leaves->isEmpty())
                <thead class="text-s text-center text-gray-700 uppercase bg-gray-50">
                    <tr>
                        <th scope="col" class="py-3 px-6 blue-color">
                            {{ __('Leave Duration Type') }}
                        </th>
                        <th scope="col" class="py-3 px-6 blue-color">
                            {{ __('From') }}
                        </th>
                        <th scope="col" class="py-3 px-6 blue-color">
                            {{ __('To') }}
                        </th>
                        <th scope="col" class="py-3 px-6 blue-color">
                            {{ __('Leave Type') }}
                        </th>
                        <th scope="col" class="py-3 px-6 blue-color">
                            {{ __('Substitute Employee') }}
                        </th>
                    </tr>
                </thead>
                <tbody class="text-center">
                    @foreach ($leaves as $leave)
                        <tr class="bg-white hover:bg-gray-50">
                            <td class="border-b py-4 px-6 text-gray-900 whitespace-nowrap">
                                <div>
                                    {{ __($leave->leave_duration->name) }}
                                </div>
                            </td>
                            <td class="border-b py-4 px-6 text-gray-900 whitespace-nowrap">
                                <div>
                                    {{ \Carbon\Carbon::createFromFormat('Y-m-d', $leave->from)->format('d/m/Y') }}
                                </div>
                            </td>
                            <td class="border-b py-4 px-6 text-gray-900 whitespace-nowrap">
                                <div>
                                    {{ \Carbon\Carbon::createFromFormat('Y-m-d', $leave->to)->format('d/m/Y') }}
                                </div>
                            </td>
                            <td class="border-b py-4 px-6 text-gray-900 whitespace-nowrap">
                                <div>
                                    {{ __($leave->leave_type->name) }}
                                </div>
                            </td>
                            <td class="border-b py-4 px-6 text-gray-900 whitespace-nowrap">
                                <div>
                                    @if ($leave->substitute_employee)
                                        {{ $leave->substitute_employee->first_name }}
                                        {{ $leave->substitute_employee->last_name }}
                                    @else
                                        -
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr class="border-gray-300">
                        <td colspan="6" class="px-4 py-8 border-t border-gray-300 text-lg">
                            <p class="text-center">{{ __('No Leaves Found') }}</p>
                        </td>
                    </tr>
                @endunless
            </tbody>
        </table>
    </div>

    <div class="mt-6 p-4">
        {{ $leaves->links() }}
    </div>
</x-sidebar>
