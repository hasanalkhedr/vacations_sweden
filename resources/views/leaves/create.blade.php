<x-sidebar>
    <div class="relative w-full h-full md:h-auto">
        <!-- Leave Days Table -->
        <div class="mx-4">
            <table class="mt-4 w-full text-sm text-left text-gray-500 border">
                <thead class="text-s uppercase bg-gray-50 blue-color">
                    <tr class="border-b">
                        <th scope="col" class="text-center py-3 px-2"></th>
                        <th scope="col" class="text-center py-3 px-2">{{ __('Remaining') }}</th>
                        <th scope="col" class="text-center py-3 px-2">{{ __('Pending') }}</th>
                        <th scope="col" class="text-center py-3 px-2">{{ __('Accepted') }}</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="bg-white">
                        <th scope="col" class="border-r-2 text-center py-3 px-2 blue-color">
                            {{ __('Leave Days') }}
                        </th>
                        <td class="text-center border-b py-4 px-2 font-bold text-gray-900 whitespace-nowrap">
                            {{ $employee->nb_of_days }}
                        </td>
                        <td class="text-center border-b py-4 px-2 font-bold text-gray-900 whitespace-nowrap">
                            {{ $normal_pending_days }}
                        </td>
                        <td class="text-center border-b py-4 px-2 font-bold text-gray-900 whitespace-nowrap">
                            {{ $normal_accepted_days }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Overtime Table -->
        <div class="mx-4">
            <table class="mt-4 w-full text-sm text-left text-gray-500 border">
                <thead class="text-s uppercase bg-gray-50 blue-color"></thead>
                <tbody>
                    <tr class="bg-white">
                        <th scope="col" class="border-r-2 text-center py-3 px-2 blue-color">
                            {{ __('Total Overtime') }}
                        </th>
                        <td class="text-center border-b py-4 px-2 font-bold text-gray-900 whitespace-nowrap">
                            {{ $overtimeTotalTime }}
                        </td>
                    </tr>
                    <tr>
                        <th scope="col" class="border-r-2 text-center py-3 px-2 blue-color">
                            {{ __('Days') }}
                        </th>
                        <td class="text-center border-b py-4 px-2 font-bold text-gray-900 whitespace-nowrap">
                            {{ $overtimeDays }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Leave Request Form -->
        <div class="p-6">
            <form method="POST" action="{{ route('leaves.store') }}" enctype="multipart/form-data">
                @csrf

                <!-- Leave Duration -->
                <div class="relative z-0 mb-6 w-full group">
                    <label for="leave_duration_id" class="mb-2 text-sm font-medium blue-color">
                        {{ __('Leave Duration') }}
                    </label>
                    <select id="leave_duration_id" name="leave_duration_id"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg
                                   focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                            onchange="calculateDateDifference();">
                        <option value="" disabled>{{ __('Choose Leave Duration') }}</option>
                        @if(count($leave_durations))
                            @foreach($leave_durations as $leave_duration)
                                <option value="{{ $leave_duration->id }}"
                                    @if($leave_duration->name == 'One or More Full Days') selected @endif>
                                    {{ __($leave_duration->name) }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                </div>

                <!-- Leave Type -->
                <div class="relative z-0 mb-6 w-full group">
                    <label for="leave_type" class="mb-2 text-sm font-medium blue-color">
                        {{ __('Select Leave Type') }}
                    </label>
                    <select id="leave_type" name="leave_type_id"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg
                                   focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                        <option value="" disabled>{{ __('Select Leave Type') }}</option>
                        @if(count($leave_types))
                            @foreach($leave_types as $leave_type)
                                <option value="{{ $leave_type->id }}">{{ __($leave_type->name) }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>

                <!-- Date Inputs -->
                <div class="grid md:grid-cols-2 md:gap-6">
                    <div class="relative z-0 w-full group flex flex-col">
                        <label id="fromDateLabel" for="fromDate" class="mb-2 text-sm font-medium blue-color">
                            {{ __('Start Date') }} <span class="text-red-500">*</span>
                        </label>
                        <input required type="text" name="from" id="fromDate"
                               placeholder="{{ __('Please select date range') }}" data-input
                               onchange="calculateDateDifference()">
                        @error('from')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="relative z-0 mb-6 w-full group flex flex-col" id="toDateDiv">
                        <label for="toDate" class="mb-2 text-sm font-medium blue-color">
                            {{ __('End Date') }} <span class="text-red-500">*</span>
                        </label>
                        <input required type="text" name="to" id="toDate"
                               placeholder="{{ __('Please select date range') }}" data-input
                               onchange="calculateDateDifference()">
                        @error('to')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <span id="error"></span>

                <!-- Travelling -->
                <div class="relative z-0 mb-6 w-full group">
                    <p class="mb-2 text-sm font-medium blue-color">{{ __('Travelling') }}</p>
                    <div class="mt-2 flex flex-row">
                        <input type="radio" id="travelling-yes" name="travelling" value="1">
                        <label for="travelling-yes" class="mx-2">{{ __('Yes') }}</label>
                        <input type="radio" id="travelling-no" name="travelling" value="0" checked>
                        <label for="travelling-no" class="mx-2">{{ __('No') }}</label>
                    </div>
                    @error('travelling')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Attachment -->
                <div class="relative z-0 mb-6 w-full group">
                    <p class="mb-2 text-sm font-medium blue-color">
                        {{ __('Attachment') }}
                        <span class="hidden text-red-500" id="attachment_file_span">*</span>
                    </p>
                    <div class="flex w-full">
                        <label class="px-2 w-max flex flex-col items-center bg-white text-white rounded-lg
                                      shadow-lg tracking-wide uppercase border border-blue cursor-pointer blue-bg">
                            <svg class="w-5 h-5" fill="white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path d="M16.88 9.1A4 4 0 0 1 16 17H5a5 5 0 0 1-1-9.9V7a3 3 0 0 1 4.52-2.59
                                         A4.98 4.98 0 0 1 17 8c0 .38-.04.74-.12 1.1zM11 11h3l-4-4-4 4h3v3h2v-3z"/>
                            </svg>
                            <span class="text-xs text-white leading-normal">{{ __('Choose File') }}</span>
                            <input type="file" name="attachment_path" id="attachment_path" class="hidden" />
                        </label>
                    </div>
                    @error('attachment_path')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Substitute -->
                <div class="relative z-0 mb-6 w-full group">
                    <label for="substitute_employee_id" class="mb-2 text-sm font-medium">
                        {{ __('Select Substitute') }}
                    </label>
                    <select id="substitute_employee_id" name="substitute_employee_id"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg
                                   focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 blue-color">
                        <option value="" disabled>{{ __('Choose Substitute Employee') }}</option>
                        <option value="">{{ __('No Replacement') }}</option>
                        @if(count($substitutes))
                            @foreach($substitutes as $substitute)
                                <option value="{{ $substitute->id }}">
                                    {{ $substitute->first_name }} {{ $substitute->last_name }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                </div>

                <!-- Actions -->
                <div class="flex justify-end items-center p-6 space-x-2 rounded-b border-t border-gray-200">
                    <div>
                        <a href="{{ url(route('leaves.submitted')) }}">
                            <button type="button"
                                    class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none
                                           focus:ring-blue-300 rounded-lg border border-gray-200 text-sm font-medium
                                           px-5 py-2.5 hover:text-gray-900 focus:z-10">
                                {{ __('Cancel') }}
                            </button>
                        </a>
                    </div>
                    <div>
                        <button id="createButton"
                                class="text-white hover:bg-blue-400 focus:ring-4 focus:outline-none focus:ring-blue-300
                                       font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center blue-bg">
                            {{ __('Create') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Pass Blocked Dates from Backend -->
    <script>
        // Existing global variables
        const blocked_dates = {!! json_encode($blocked_dates) !!};
        const holiday_dates = {!! json_encode($holiday_dates) !!};
        const disabled_dates = {!! json_encode($disabled_dates) !!};
        const weekdays_off = {!! json_encode(phpToJsWeekdayArray(auth()->user()->weekdays_off)) !!};

        const userOvertimeDays = {{ (int) $overtimeDays }};
        const userNbOfDays = {{ auth()->user()->nb_of_days }};
        const normalPendingDaysWithoutRecovery = {{ $normal_pending_days_without_recovery }};
        const userRemainingDays = {{ $employee->nb_of_days }};
        const recoveryLeaveTypeId = {{ $leave_types->firstWhere('name', 'recovery')->id }};

        // New variable to determine if we skip blocked_dates check
        let skipBlockedDatesCheck = false;
    </script>

    <script type="text/javascript">
        // Utility Functions
        function changeDateFormat(date) {
            if (!date) return null;
            const [day, month, year] = date.split('/');
            return `${year}-${month}-${day}`;
        }

        function isDateDisabled(date) {
            const date_temp = new Date(date.getTime());
            const disabled_date = new Date(Date.parse(new Date(date_temp.setDate(date_temp.getDate() + 1)))).toISOString().split('T')[0];

            // If skipBlockedDatesCheck is true, do not check blocked_dates.
            const isWeekendOrOther = weekdays_off.includes(date.getDay()) ||
                                    holiday_dates.includes(disabled_date) ||
                                    disabled_dates.includes(disabled_date);

            // Check blocked_dates only if skipBlockedDatesCheck is false
            const isInBlockedDates = !skipBlockedDatesCheck && blocked_dates.includes(disabled_date);

            return isWeekendOrOther || isInBlockedDates;
        }

        function disableButtonAndShowError(text) {
            $('#createButton').attr('disabled', true).addClass('disabled-button');
            $('#error').css("color", "red").text(text);
        }

        function calculateDateDifference() {
            // Reset errors
            $('#createButton').attr('disabled', false).removeClass('disabled-button');
            $("#error").text("");

            let fromVal = $('#fromDate').val();
            let toVal = $('#toDate').val();

            if (!fromVal && !toVal) return;

            let fromDate = changeDateFormat(fromVal);
            let toDate = changeDateFormat(toVal);

            if (fromDate > toDate || !toDate) {
                $("#toDate").val(fromVal);
                toDate = changeDateFormat($('#toDate').val());
            } else if (!fromDate) {
                $("#fromDate").val(toVal);
                fromDate = changeDateFormat($('#fromDate').val());
            }

            const start = new Date(fromDate);
            const end = new Date(toDate);
            let dateDifference = ((end.getTime() - start.getTime()) / (1000 * 3600 * 24)) + 1;

            let tempDate = new Date(start.getTime());
            while (tempDate <= end) {
                if (isDateDisabled(tempDate)) dateDifference--;
                tempDate.setDate(tempDate.getDate() + 1);
            }

            const selectedLeaveTypeText = $("#leave_type option:selected").text().toLowerCase();
            const selectedLeaveDurationText = $("#leave_duration_id option:selected").text().toLowerCase();

            // Check conditions for recovery leaves
            if (selectedLeaveTypeText === "recovery") {
                handleRecoveryLeaveChecks(dateDifference, selectedLeaveDurationText);
            }
            // Check other leave types (excluding remote work or handling them as well)
            else if (selectedLeaveTypeText !== "remote work") {
                handleRegularLeaveChecks(dateDifference, selectedLeaveDurationText);
            }
        }

        function handleRecoveryLeaveChecks(dateDifference, leaveDurationText) {
            // Recovery leave checks against overtime days
            if (leaveDurationText.includes("full days")) {
                // One or More Full Days
                if (dateDifference > userOvertimeDays) {
                    let text = "{{ __('You chose a range of') }} " + dateDifference +
                               " {{ __('days but you only have') }} " + userOvertimeDays +
                               " {{ __('leave days left') }}";
                    disableButtonAndShowError(text);
                }
            } else {
                // Half-day scenario
                if (dateDifference > (2 * userOvertimeDays)) {
                    let text = "{{ __('You chose a range of') }} " + (dateDifference / 2) +
                               " {{ __('days but you only have') }} " + userOvertimeDays +
                               " {{ __('leave days left') }}";
                    disableButtonAndShowError(text);
                }
            }
        }

        function handleRegularLeaveChecks(dateDifference, leaveDurationText) {
            // Regular leave checks
            if (leaveDurationText.includes("full days")) {
                if (dateDifference > userNbOfDays) {
                    let text = "{{ __('You chose a range of') }} " + dateDifference +
                               " {{ __('days but you only have') }} " + userNbOfDays +
                               " {{ __('leave days left') }}";
                    disableButtonAndShowError(text);
                }
            } else {
                // Half-day scenario
                if (dateDifference > (2 * userNbOfDays)) {
                    let text = "{{ __('You chose a range of') }} " + (dateDifference / 2) +
                               " {{ __('days but you only have') }} " + userNbOfDays +
                               " {{ __('leave days left') }}";
                    disableButtonAndShowError(text);
                }
            }
        }

        function checkBalanceBeforeSubmit() {
            // Reset errors
            $('#createButton').attr('disabled', false).removeClass('disabled-button');
            $("#error").text("");

            const leaveType = $('#leave_type').val();
            const fromDate = $('#fromDate').val();
            const toDate = $('#toDate').val();

            // Skip check if it's a recovery leave
            if (parseInt(leaveType) === recoveryLeaveTypeId) {
                return true;
            }

            const remainingDays = userRemainingDays;
            const totalPending = parseInt(normalPendingDaysWithoutRecovery);

            const start = new Date(changeDateFormat(fromDate));
            const end = new Date(changeDateFormat(toDate));
            const requestedDays = ((end.getTime() - start.getTime()) / (1000 * 3600 * 24)) + 1;

            if (remainingDays - totalPending < requestedDays) {
                let errorText = "{{ __('You have insufficient balance to submit this request. ' .
                                      'You may have already booked more days than available in your balance.') }}";
                disableButtonAndShowError(errorText);
                return false;
            }
            return true;
        }

        // Form Submission
        $('form').on('submit', function(event) {
            if (!checkBalanceBeforeSubmit()) {
                event.preventDefault();
            }
        });
    </script>

    <script type="text/javascript">
        // Initialize Date Pickers with disabled logic
        const frompicker = $("#fromDate").flatpickr({
            dateFormat: "d/m/Y",
            disable: [
                function(date) {
                    return isDateDisabled(date);
                }
            ],
            locale: { firstDayOfWeek: 1 },
            allowInput: true,
            onClose: function(selectedDates, dateStr) {
                if (dateStr) {
                    topicker.set('minDate', dateStr);
                }
            },
        });

        const topicker = $("#toDate").flatpickr({
            dateFormat: "d/m/Y",
            disable: [
                function(date) {
                    return isDateDisabled(date);
                }
            ],
            locale: { firstDayOfWeek: 1 },
            allowInput: true,
        });
    </script>

    <script>
        // Handle Leave Type changes (e.g., sick leave requires attachment)
        $("#leave_type").on('change', function() {
            const selectedTypeText = this.options[this.selectedIndex].text.toLowerCase();
            const attachmentSpan = $('#attachment_file_span')[0];
            const attachmentInput = document.getElementById("attachment_path");

            // Show attachment requirement for sick leave
            if (selectedTypeText === "sick leave") {
                attachmentSpan.classList.remove('hidden');
                attachmentInput.required = true;
            } else {
                attachmentSpan.classList.add('hidden');
                attachmentInput.required = false;
            }

            // If leave type is sick or bereavement, skip disabled_dates check
            if (['sick leave', 'bereavement leave'].includes(selectedTypeText)) {
                skipBlockedDatesCheck = true;
            } else {
                skipBlockedDatesCheck = false;
            }

            // Re-calculate date difference in case changes in filtering
            calculateDateDifference();
        });
    </script>
</x-sidebar>
