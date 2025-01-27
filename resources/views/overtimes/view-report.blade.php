<x-sidebar>
    @section('title', __("Overtime Report"))
    <div class="overflow-x-auto relative shadow-md">
        <div class="my-4 text-center">
            <div class="text-lg blue-color">
                {{__("Name") }}: {{ $employee->first_name }} {{ $employee->last_name }}
            </div>
            <div class="text-lg blue-color">
                {{__("Department") }}: {{ ucfirst(strtolower($employee->department->name)) }}
            </div>
        </div>
        <table class="w-full text-sm text-left text-gray-500">
            @unless($overtimes->isEmpty())
                <thead class="text-s text-center text-gray-700 uppercase bg-gray-50">
                <tr>
                    <th scope="col" class="py-3 px-6 blue-color">
                        {{__("Day")}}
                    </th>
                    <th scope="col" class="py-3 px-6 blue-color">
                        {{__("Date")}}
                    </th>
                    <th scope="col" class="py-3 px-6 blue-color">
                        {{__("From")}}
                    </th>
                    <th scope="col" class="py-3 px-6 blue-color">
                        {{__("To")}}
                    </th>
                    <th scope="col" class="py-3 px-6 blue-color">
                        {{__("Overtime")}}
                    </th>
                </tr>
                </thead>
                <tbody class="text-center">
                @foreach ($overtimes as $overtime)
                    <tr class="bg-white hover:bg-gray-50">
                        <td class="border-b py-4 px-6 text-gray-900 whitespace-nowrap">
                            <div>
                                {{ __(\Carbon\Carbon::createFromFormat('d/m/Y', $overtime->date)->format('l')) }}
                            </div>
                        </td>
                        <td class="border-b py-4 px-6 text-gray-900 whitespace-nowrap">
                            <div>
                                {{ $overtime->date }}
                            </div>
                        </td>
                        <td class="border-b py-4 px-6 text-gray-900 whitespace-nowrap">
                            <div>
                                {{\Carbon\Carbon::createFromFormat('H:i:s',$overtime->from)->format('h:i')}}
                            </div>
                        </td>
                        <td class="border-b py-4 px-6 text-gray-900 whitespace-nowrap">
                            <div>
                                {{\Carbon\Carbon::createFromFormat('H:i:s',$overtime->to)->format('h:i')}}
                            </div>
                        </td>
                        <td class="border-b py-4 px-6 text-gray-900 whitespace-nowrap">
                            <div>
                                {{\Carbon\Carbon::createFromFormat('H:i:s',$overtime->hours)->format('h:i')}}
                            </div>
                        </td>
                    </tr>
                @endforeach
                <tr class="bg-white text-gray-900 hover:bg-gray-50 border-2 border-blue-800">
                    <td colspan="4" class="text-center font-bold py-4 px-6 whitespace-nowrap">
                        <div>
                            {{__("Total")}}
                        </div>
                    </td>
                    <td colspan="4" class="font-bold py-4 px-6 whitespace-nowrap">
                        <div>
                            {{ $total_time }}
                        </div>
                    </td>
                </tr>
                @else
                    <tr class="border-gray-300">
                        <td colspan="4" class="px-4 py-8 border-t border-gray-300 text-lg">
                            <p class="text-center">{{__("No Overtimes Found")}}</p>
                        </td>
                    </tr>
                @endunless
                </tbody>
        </table>
    </div>
</x-sidebar>
