<x-sidebar>
    @section('title', __('Calendar Form'))
    <form method="POST" action="{{ route('leaves.generateCalendar') }}" enctype="multipart/form-data"
        class="m-2 bg-white shadow-md rounded-lg p-6">
        @csrf
        <div class="flex flex-wrap -mx-3 mb-4">
            <div class="w-full md:w-1/3 px-3 mb-4 md:mb-0">
                <label for="month" class="block text-lg font-semibold text-gray-700 mb-2">
                    {{ __('Select a month') }}
                </label>
                <select name="month"
                    class="w-full bg-white border border-gray-300 rounded-md py-2 px-3 shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    @foreach ($months as $month)
                        @if ($month[0] == \Carbon\Carbon::now()->month)
                            <option value="{{ $month[0] }}" selected>{{ __($month[1]) }}</option>
                        @else
                            <option value="{{ $month[0] }}">{{ __($month[1]) }}</option>
                        @endif
                    @endforeach
                </select>
            </div>
            <div class="w-full md:w-1/3 px-3 mb-4 md:mb-0">
                <label for="year" class="block text-lg font-semibold text-gray-700 mb-2">
                    {{ __('Select a year') }}
                </label>
                <select name="year"
                    class="w-full bg-white border border-gray-300 rounded-md py-2 px-3 shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    @for ($i = now()->year; $i >= 2023; $i--)
                        <option value="{{ $i }}" @if (now()->year == $i) selected @endif>
                            {{ $i }}
                        </option>
                    @endfor
                </select>
            </div>
            @hasanyrole('human_resource|sg|head')
                <div class="w-full md:w-1/3 px-3">
                    <label for="department_id" class="block text-lg font-semibold text-gray-700 mb-2">
                        {{ __('Select Department') }}
                    </label>
                    <select name="department_id"
                        class="w-full bg-white border border-gray-300 rounded-md py-2 px-3 shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        <option value="all">All</option>
                        @if (count($departments))
                            @foreach ($departments as $department)
                                <option value="{{ $department->id }}">{{ $department->name }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
            @endhasanyrole
        </div>
        <div class="mt-6">
            <button type="submit"
                class="w-full hover:bg-blue-400 blue-bg hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                {{ __('Generate New Calendar') }}
            </button>
        </div>
    </form>
</x-sidebar>
