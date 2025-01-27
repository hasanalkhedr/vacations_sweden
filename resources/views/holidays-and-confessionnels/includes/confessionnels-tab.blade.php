<div>
    <div class="px-4 overflow-x-auto relative shadow-md sm:rounded-lg">
        <table class="w-full text-sm text-left text-gray-500">
            @unless($confessionnels->isEmpty())
                <thead class="text-s blue-color uppercase bg-gray-50">
                <tr>
                    <th scope="col" class="cursor-pointer py-3 px-6">
                        {{__("Name")}}
                    </th>
                    <th scope="col" class="cursor-pointer py-3 px-6">
                        {{__("Date")}}
                    </th>
                </tr>
                </thead>
                <tbody>
                @foreach ($confessionnels as $confessionnel)
                    <tr class="bg-white hover:bg-gray-50">
                        <td class="border-b py-4 px-6 font-bold text-gray-900 whitespace-nowrap cursor-pointer" onclick="window.location.href = '{{ url(route('confessionnels.show', ['confessionnel' => $confessionnel->id])) }}'">
                            {{ $confessionnel->name }}
                        </td>
                        <td class="py-4 px-6 border-b cursor-pointer" onclick="window.location.href = '{{ url(route('confessionnels.show', ['confessionnel' => $confessionnel->id])) }}'">
                            {{ \Carbon\Carbon::createFromFormat('Y-m-d', $confessionnel->date)->format(config('app.date_format')) }}
                        </td>
                    </tr>
                @endforeach
                @else
                    <tr class="border-gray-300">
                        <td colspan="4" class="px-4 py-8 border-t border-gray-300 text-lg">
                            <p class="text-center">{{__("No Confessionnels Found")}}</p>
                        </td>
                    </tr>
                @endunless
                </tbody>
        </table>


    </div>

    <div class="mt-6 p-4">
        {{ $confessionnels->appends(['holidays_page' => $holidays->currentPage(), 'active_tab' => 'confessionnels'])->links() }}
    </div>
</div>
