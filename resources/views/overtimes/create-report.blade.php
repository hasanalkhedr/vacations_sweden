<x-sidebar>
    @section('title', __("Overtimes Report Form"))
    <form method="POST" action="{{ route('overtimes.generateReport') }}" enctype="multipart/form-data" class="m-2">
        @csrf
        <div>
            <label for="employee_id" class="text-lg block mb-2 text-sm font-medium blue-color">{{__("Select Employee")}}</label>
            <select name="employee_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                @if(count($employees))
                    @foreach ($employees as $employee)
                        <option value="{{ $employee->id }}">{{ $employee->first_name }} {{ $employee->last_name }}</option>
                    @endforeach
                @endif
            </select>
        </div>
        <div class="grid md:grid-cols-2 md:gap-6 mt-2">
            <div class="relative z-0 w-full group flex flex-col">
                <label for="fromDate" class="mb-2 text-sm font-medium blue-color">
                    {{__("Start Date")}}
                </label>
                <input required type="text" name="from_date" id="fromDate" placeholder="{{__("Please select date range")}}" data-input>
                @error('from')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="relative z-0 mb-6 w-full group flex flex-col" id="toDateDiv">
                <label for="toDate" class="mb-2 text-sm font-medium blue-color">
                    {{__("End Date")}}
                </label>
                <input required type="text" name="to_date" id="toDate" placeholder="{{__("Please select date range")}}" data-input>
                @error('to')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>
        <button class="mt-4 text-white hover:text-white border hover:bg-blue-400 focus:ring-2 focus:outline-none focus:ring-gray-100 font-medium rounded-lg text-sm px-5 py-2.5 text-center mb-2 blue-bg">
            {{__("Generate Report")}}
        </button>
    </form>

    <script type="text/javascript">
        let frompicker = $("#fromDate").flatpickr({
            dateFormat: "d/m/Y",
            locale: {
                firstDayOfWeek: 1
            },
            allowInput:true,
            onClose: function (dateStr, dateObj) {
                if (dateStr) {
                    topicker.set('minDate', dateStr);
                    topicker.setDate(dateObj);
                }
            },
        });

        let topicker = $("#toDate").flatpickr({
            dateFormat: "d/m/Y",
            locale: {
                firstDayOfWeek: 1,
            },
            allowInput:true,
        });

        $('#toDate').change(function() {
            let fromDate = $('#fromDate').val();
            let toDate = $('#toDate').val();
            if (!fromDate) {
                $("#fromDate").val(toDate);
                fromDate = $('#fromDate').val();
                topicker.set('minDate', $('#fromDate').val());
            }
        })
    </script>
</x-sidebar>
