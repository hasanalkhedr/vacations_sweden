<x-sidebar>
    @section('title', __('Blocked Days'))
    @push('head')
    <script src="https://unpkg.com/flowbite@1.5.3/dist/datepicker.js"></script>
    @endpush
    <nav class="flex justify-between items-center p-2 font-bold blue-color">
        <div class="text-lg">
            {{ __('Blocked Days') }}
        </div>
        <div>
            <button class="hover:bg-blue-400 text-white py-2 px-4 rounded-full blue-bg"
                data-modal-toggle="createModal">
                {{ __('Add Blocked Day') }}
            </button>
        </div>
    </nav>
    @include('partials.searches._search-holidays')
    <div class="px-4 overflow-x-auto relative shadow-md sm:rounded-lg">
        <table x-data="data()" class="w-full text-sm text-left text-gray-500" x-data="holidayData">
            @unless($blocked_days->isEmpty())
            <thead class="text-s blue-color uppercase bg-gray-50">
                <tr>
                    <th @click="sortByColumn" scope="col" class="cursor-pointer py-3 px-6">
                        {{ __('Name') }}
                    </th>
                    <th @click="sortByColumn" scope="col" class="cursor-pointer py-3 px-6">
                        {{ __('From') }}
                    </th>
                    <th @click="sortByColumn" scope="col" class="cursor-pointer py-3 px-6">
                        {{ __('To') }}
                    </th>
                    <th scope="col" class="py-3 px-6 blue-color">
                        <span class="sr-only">{{ __('Edit') }}</span>
                    </th>
                    <th scope="col" class="py-3 px-6">
                        <span class="sr-only">{{ __('Delete') }}</span>
                    </th>
                </tr>
            </thead>
            <tbody x-ref="tbody">
                @foreach ($blocked_days as $blocked_day)
                <tr class="bg-white">
                    <td class="border-b py-4 px-6 font-bold text-gray-900 whitespace-nowrap cursor-pointer"
                        onclick="window.location.href = '{{ url(route('blocked-days.show', ['blocked_day' => $blocked_day->id])) }}'">
                        {{ $blocked_day->name }}
                    </td>
                    <td class="py-4 px-6 border-b cursor-pointer"
                        onclick="window.location.href = '{{ url(route('blocked-days.show', ['blocked_day' => $blocked_day->id])) }}'">
                        {{ $blocked_day->from }}
                    </td>
                    <td class="py-4 px-6 border-b cursor-pointer"
                        onclick="window.location.href = '{{ url(route('blocked-days.show', ['blocked_day' => $blocked_day->id])) }}'">
                        {{ $blocked_day->to }}
                    </td>
                    @hasanyrole('human_resource|sg')
                    <td class="py-4 px-6 text-right border-b">
                        <button class="font-medium text-blue-600 hover:underline" type="button"
                            data-modal-toggle="editProfileModal-{{ $blocked_day->id }}">
                            {{ __('Edit') }}
                        </button>
                    </td>
                    <td class="py-4 px-6 text-right border-b">
                        <button class="font-medium text-red-600 hover:underline" type="button"
                            data-modal-toggle="deleteModal-{{ $blocked_day->id }}">
                            {{ __('Delete') }}
                        </button>
                    </td>
                    @endhasanyrole

                    <div id="deleteModal-{{ $blocked_day->id }}" tabindex="-1" aria-hidden="true"
                        class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 w-full md:inset-0 h-modal md:h-full">
                        <div class="relative p-4 w-full max-w-2xl h-full md:h-auto">
                            <!-- Modal content -->
                            <div class="relative bg-white rounded-lg shadow">
                                <!-- Modal header -->
                                <div class="flex justify-between items-center p-4 rounded-t border-b">
                                    <div
                                        class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                        <svg @click="toggleModal" class="h-6 w-6 text-red-600"
                                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                        </svg>
                                    </div>
                                    <div class="text-base font-bold mt-3 sm:mt-0 sm:ml-4 sm:text-left">
                                        {{ __('Delete Blocked Day') }}: {{ $blocked_day->name }}
                                    </div>
                                    <div>
                                        <button type="button"
                                            class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center"
                                            data-modal-toggle="deleteModal-{{ $blocked_day->id }}">
                                            <svg aria-hidden="true" class="w-5 h-5" fill="currentColor"
                                                viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd"
                                                    d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                                    clip-rule="evenodd"></path>
                                            </svg>
                                            <span class="sr-only">Close modal</span>
                                        </button>
                                    </div>
                                </div>
                                <!-- Modal body -->
                                <div class="p-6 space-y-6">
                                    <div class="text-base leading-relaxed text-gray-500">
                                        {{ __('Are you sure you want to delete this blocked_day') }}?
                                        {{ __("This action cannot
                                                                                                                                            be undone") }}.
                                    </div>
                                </div>
                                <!-- Modal footer -->
                                <div
                                    class="flex justify-end items-center p-6 space-x-2 rounded-b border-t border-gray-200">
                                    <div>
                                        <button data-modal-toggle="deleteModal-{{ $blocked_day->id }}" type="button"
                                            class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10">
                                            {{ __('Cancel') }}
                                        </button>
                                    </div>
                                    <div>
                                        <form method="POST"
                                            action="{{ route('blocked-days.destroy', ['blocked_day' => $blocked_day->id]) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button data-modal-toggle="deleteModal-{{ $blocked_day->id }}"
                                                class="text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                                                {{ __('Delete') }}
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="editProfileModal-{{ $blocked_day->id }}" tabindex="-1" aria-hidden="true"
                        class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 w-full md:inset-0 h-modal md:h-full">
                        <div class="relative p-4 w-full max-w-2xl h-full md:h-auto">
                            <!-- Modal content -->
                            <div class="relative bg-white rounded-lg shadow">
                                <!-- Modal header -->
                                <div class="flex justify-between items-center p-4 rounded-t border-b">
                                    <div class="text-base font-bold mt-3 sm:mt-0 sm:ml-4 sm:text-left blue-color">
                                        {{ __('Edit Blocked Day') }}: {{ $blocked_day->name }}
                                    </div>
                                    <div>
                                        <button type="button"
                                            class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center"
                                            data-modal-toggle="editProfileModal-{{ $blocked_day->id }}">
                                            <svg aria-hidden="true" class="w-5 h-5" fill="currentColor"
                                                viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd"
                                                    d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                                    clip-rule="evenodd"></path>
                                            </svg>
                                            <span class="sr-only">Close modal</span>
                                        </button>
                                    </div>
                                </div>
                                <!-- Modal body -->
                                <div class="p-6">
                                    <form method="POST"
                                        action="{{ route('blocked-days.update', ['blocked_day' => $blocked_day->id]) }}">
                                        @csrf
                                        @method('PUT')
                                        <div class="relative z-0 w-full group flex flex-col">
                                            <label for="from"
                                                class="mb-2 text-sm font-medium blue-color">{{ __('Name') }}</label>
                                            <input type="text" name="name"
                                                placeholder="Please enter blocked_day's name"
                                                value="{{ $blocked_day->name }}">
                                            @error('name')
                                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="grid md:grid-cols-2 md:gap-6 my-3">
                                            <div class="relative z-0 w-full group flex flex-col">
                                                <label for="from" class="mb-2 text-sm font-medium blue-color">
                                                    {{ __('Start Date') }}
                                                </label>
                                                <input type="text" name="from" id="fromDateEdit-{{$blocked_day->id}}"
                                                    placeholder={{ __('Please select date range') }} data-input
                                                    value="{{ $blocked_day->from }}">
                                                @error('from')
                                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                                @enderror
                                            </div>
                                            <div class="relative z-0 mb-6 w-full group flex flex-col">
                                                <label for="to" class="mb-2 text-sm font-medium blue-color">
                                                    {{ __('End Date') }}
                                                </label>
                                                <input type="text" name="to" id="toDateEdit-{{$blocked_day->id}}"
                                                    placeholder={{ __('Please select date range') }} data-input
                                                    value="{{ $blocked_day->to }}">
                                                @error('to')
                                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>
                                        <div
                                            class="flex justify-end items-center p-6 space-x-2 rounded-b border-t border-gray-200">
                                            <div>
                                                <button data-modal-toggle="editProfileModal-{{ $blocked_day->id }}"
                                                    type="button"
                                                    class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10">
                                                    {{ __('Cancel') }}
                                                </button>
                                            </div>
                                            <div>
                                                <button
                                                    class="text-white hover:bg-blue-400 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center blue-bg"
                                                    data-modal-toggle="editProfileModal-{{ $blocked_day->id }}">{{ __('Edit') }}
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </tr>
                @endforeach
                @else
                <tr class="border-gray-300">
                    <td colspan="4" class="px-4 py-8 border-t border-gray-300 text-lg">
                        <p class="text-center">{{ __('No Blocked Days Found') }}</p>
                    </td>
                </tr>
                @endunless
            </tbody>
        </table>

        <div id="createModal" tabindex="-1" aria-hidden="true"
            class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 w-full md:inset-0 h-modal md:h-full">
            <div class="relative p-4 w-full max-w-2xl h-full md:h-auto">
                <!-- Modal content -->
                <div class="relative bg-white rounded-lg shadow">
                    <!-- Modal header -->
                    <div class="flex justify-between items-center p-4 rounded-t border-b">
                        <div class="text-base font-bold mt-3 sm:mt-0 sm:ml-4 sm:text-left">
                            {{ __('Add Blocked Day') }}
                        </div>
                        <div>
                            <button type="button"
                                class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center"
                                data-modal-toggle="createModal">
                                <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd"
                                        d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                <span class="sr-only">Close modal</span>
                            </button>
                        </div>
                    </div>
                    <!-- Modal body -->
                    <div class="p-6">
                        <form method="POST" action="{{ route('blocked-days.store') }}">
                            @csrf
                            <div class="relative z-0 w-full group flex flex-col">
                                <label for="from"
                                    class="mb-2 text-sm font-medium text-gray-900">{{ __('Name') }}</label>
                                <input type="text" name="name" placeholder={{ __('Name') }}>
                                @error('name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="grid md:grid-cols-2 md:gap-6 my-3">
                                <div class="relative z-0 w-full group flex flex-col">
                                    <label for="from" class="mb-2 text-sm font-medium text-gray-900">
                                        {{ __('Start Date') }}
                                    </label>
                                    <input type="text" name="from" id="fromDateStore"
                                        placeholder={{ __('Please select date range') }} data-input>
                                    @error('from')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="relative z-0 mb-6 w-full group flex flex-col">
                                    <label for="to" class="mb-2 text-sm font-medium text-gray-900">
                                        {{ __('End Date') }}
                                    </label>
                                    <input type="text" name="to" id="toDateStore"
                                        placeholder={{ __('Please select date range') }} data-input>
                                    @error('to')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <div
                                class="flex justify-end items-center p-6 space-x-2 rounded-b border-t border-gray-200">
                                <div>
                                    <button data-modal-toggle="createModal" type="button"
                                        class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10">
                                        {{ __('Cancel') }}
                                    </button>
                                </div>
                                <div>
                                    <button
                                        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center">
                                        {{ __('Create') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="mt-6 p-4">
        {{ $blocked_days->links() }}
    </div>


    <script type="text/javascript">
        function data() {
            return {
                sortBy: "",
                sortAsc: false,
                sortByColumn($event) {
                    if (this.sortBy === $event.target.innerText) {
                        if (this.sortAsc) {
                            this.sortBy = "";
                            this.sortAsc = false;
                        } else {
                            this.sortAsc = !this.sortAsc;
                        }
                    } else {
                        this.sortBy = $event.target.innerText;
                    }

                    let rows = this.getTableRows()
                        .sort(
                            this.sortCallback(
                                Array.from($event.target.parentNode.children).indexOf(
                                    $event.target
                                )
                            )
                        )
                        .forEach((tr) => {
                            this.$refs.tbody.appendChild(tr);
                        });
                },
                getTableRows() {
                    return Array.from(this.$refs.tbody.querySelectorAll("tr"));
                },
                getCellValue(row, index) {
                    return row.children[index].innerText;
                },
                sortCallback(index) {
                    return (a, b) =>
                        ((row1, row2) => {
                            return row1 !== "" &&
                                row2 !== "" &&
                                !isNaN(row1) &&
                                !isNaN(row2) ?
                                row1 - row2 :
                                row1.toString().localeCompare(row2);
                        })(
                            this.getCellValue(this.sortAsc ? a : b, index),
                            this.getCellValue(this.sortAsc ? b : a, index)
                        );
                }
            };
        }
    </script>

    <script>
        $('#fromDateEdit').change(function() {
            let fromDate = $('#fromDateEdit').val();
            let toDate = $('#toDateEdit').val();
            if (!toDate) {
                $("#toDateEdit").val(fromDate);
                toDate = $('#toDateEdit').val();
            }
        })

        $('#toDateEdit').change(function() {
            let fromDate = $('#fromDateEdit').val();
            let toDate = $('#toDateEdit').val();
            if (!fromDate) {
                $("#fromDateEdit").val(toDate);
                fromDate = $('#fromDateEdit').val();
            }
        })

        $('#fromDateStore').change(function() {
            let fromDate = $('#fromDateStore').val();
            let toDate = $('#toDateStore').val();
            if (!toDate) {
                $("#toDateStore").val(fromDate);
                toDate = $('#toDateStore').val();
            }
        })

        $('#toDateStore').change(function() {
            let fromDate = $('#fromDateStore').val();
            let toDate = $('#toDateStore').val();
            if (!fromDate) {
                $("#fromDateStore").val(toDate);
                fromDate = $('#fromDateStore').val();
            }
        })
    </script>

    <script type="text/javascript">
        let frompicker = $("#fromDateStore").flatpickr({
            dateFormat: "Y-m-d",
            disable: [
                function(date) {
                    let date_temp = new Date(date.getTime());
                    let disabled_date = new Date(Date.parse(new Date(date_temp.setDate(date_temp.getDate() + 1)))).toISOString().split('T')[0];
                    return ({!! json_encode($blocked_day_dates) !!}.includes(disabled_date));
                }
            ],
            locale: {
                firstDayOfWeek: 1
            },
            onClose: function(selectedDates, dateStr, instance) {
                topicker.set('minDate', dateStr);
            },
            onChange: function(selectedDates, dateStr, instance) {
                topicker.set('minDate', dateStr);
            },
        });

        let topicker = $("#toDateStore").flatpickr({
            dateFormat: "Y-m-d",
            disable: [
                function(date) {
                    let date_temp = new Date(date.getTime());
                    let disabled_date = new Date(Date.parse(new Date(date_temp.setDate(date_temp.getDate() + 1)))).toISOString().split('T')[0];
                    return ({!! json_encode($blocked_day_dates) !!}.includes(disabled_date));
                }
            ],
            locale: {
                firstDayOfWeek: 1
            }
        });
    </script>
</x-sidebar>