<x-sidebar>
    @section('title', __("Create Overtime Submission"))
    <div class="relative w-full h-full md:h-auto">
        <div class="p-6">
            <form method="POST" action="{{ route('overtimes.store') }}">
                @csrf
                <table class="table w-full text-sm text-left text-gray-500">
                    <thead class="p-2 text-s uppercase bg-gray-50 blue-color">
                    <tr>
                        <th class="py-3">{{__("Date")}}</th>
                        <th class="py-3">{{__("From")}}</th>
                        <th class="py-3">{{__("To")}}</th>
                        <th class="py-3">{{__("Hours")}}</th>
                        <th class="py-3">{{__("Objective")}}</th>
                        <th class="py-3">{{__("Select")}}</th>
                    </tr>
                    </thead>
                    <tbody id="tbody">

                    </tbody>
                </table>
                <div class="mx-6 flex justify-between">
                    <button type="button" onclick="addOvertime();" class="mt-4 text-white border hover:bg-blue-400 focus:outline-none font-medium rounded-full text-sm px-5 py-2.5 mr-2 mb-2 blue-bg">
                        {{__("Add Overtime")}}
                    </button>
                    <button class="mt-4 text-white border border-blue-400 focus:outline-none hover:bg-blue-400 font-medium rounded-full text-sm px-5 py-2.5 mr-2 mb-2 blue-bg">
                        {{__("Submit")}}
                    </button>
                </div>
            </form>
        </div>

    </div>

    <script>
        let overtimes = 0;
        let multiplyHours = false;
        let MULTIPLIER = 1.5;
        function addOvertime() {
            overtimes++;

            let html = "<tr class='bg-white hover:bg-gray-50'>";
            html += "<td class='py-4 border-b'><input type='text' class='date border-none' placeholder='{{__("Please select date")}}' name='date[]' data-input></td>";
            html += "<td class='py-4 border-b'><input type='time' class='from border-none' id='overtimeFrom' name='from[]'></td>";
            html += "<td class='py-4 border-b'><input type='time' class='to border-none' name='to[]'></td>";
            html += "<td class='py-4 border-b'><input type='text' class='focus:ring-0 border-none' name='hours[]' readonly></td>";
            html += "<td class='py-4 border-b'><textarea name='objective[]'></textarea></td>";
            html += "<td class='py-4 border-b font-semibold text-red-500'><button type='button' onclick='deleteRow(this);'>{{__("Delete")}}</button></td>"
            html += "</tr>";

            var row = document.getElementById("tbody").insertRow();
            row.innerHTML = html;
            let frompicker = $(".date").flatpickr({
                dateFormat: "d/m/Y",
                locale: {
                    firstDayOfWeek: 1
                },
            });
        }

        function changeDateFormat(date) {
            if(!date) {
                return null
            }
            let separator = '/';
            const [day, month, year] = date.split(separator);

            const formattedDate = [year, month, day].join('-');

            return formattedDate;
        }

        function deleteRow(button) {
            overtimes--
            button.parentElement.parentElement.remove();
            // first parentElement will be td and second will be tr.
        }

        $('.table').on('change', '.date', function () {
            multiplyHours = false;
            let $row = $(this).closest("tr");
            let date = $row.find("input[name^='date']");
            let day = new Date(changeDateFormat(date.val()))
            const offset = day.getTimezoneOffset()
            day = new Date(day.getTime() - (offset*60*1000))
            let string_day = day.toISOString().split('T')[0]
            if(day.getDay() == 0 || {!! json_encode($holiday_dates) !!}.includes(string_day)) {
                multiplyHours = true;
            }
            let from = $row.find("input[name^='from']");
            let to = $row.find("input[name^='to']");
            if($row.find("input[name^='hours']").val() != '' && from.val() != '' && to.val() != '') {
                let val = calculateOvertimeHours(from, to);
                $row.find("input[name^='hours']").val(val);
            }
        })

        $('.table').on('change', '.from', function () {
            let $row = $(this).closest("tr");
            let from = $row.find("input[name^='from']");
            let to = $row.find("input[name^='to']");
            if(to.val() != '') {
                let val = calculateOvertimeHours(from, to);
                $row.find("input[name^='hours']").val(val);
            }
        })

        $('.table').on('change', '.to', function () {
            let $row = $(this).closest("tr");
            let from = $row.find("input[name^='from']");
            let to = $row.find("input[name^='to']");
            if(from.val() != '') {
                let val = calculateOvertimeHours(from, to);
                $row.find("input[name^='hours']").val(val);
            }
        })

// function calculateOvertimeHours(fromTime, toTime) {
        //     let from = fromTime.val().split(':');
        //     let to = toTime.val().split(':');
        //     let startDate = new Date(0, 0, 0, from[0], from[1], 0);
        //     let endDate = new Date(0, 0, 0, to[0], to[1], 0);
        //     let diff = endDate.getTime() - startDate.getTime();
        //     let hours = Math.floor(diff / 1000 / 60 / 60);
        //     diff -= hours * 1000 * 60 * 60;
        //     let minutes = Math.floor(diff / 1000 / 60);

        //     // If using time pickers with 24 hours format, add the below line get exact hours
        //     if (hours < 0)
        //         hours = hours + 24;
        //     if(multiplyHours) {
        //         let total_minutes = Math.ceil((hours*60 + minutes) * MULTIPLIER);
        //         hours = Math.floor(total_minutes/60);
        //         minutes = total_minutes % 60;
        //     }
        //     return (hours <= 9 ? "0" : "") + hours + ":" + (minutes <= 9 ? "0" : "") + minutes;
        // }
        function calculateOvertimeHours(fromTime, toTime) {
            // Parse input times with AM/PM consideration
            let parseTime = (timeStr) => {
                let [time, period] = timeStr.split(/(am|pm)/i).filter(Boolean);
                let [hours, minutes] = time.split(':').map(Number);
                if (period?.toLowerCase() === 'pm' && hours < 12) hours += 12;
                if (period?.toLowerCase() === 'am' && hours === 12) hours = 0;
                return {
                    hours,
                    minutes
                };
            };

            let from = parseTime(fromTime.val());
            let to = parseTime(toTime.val());
            let startHour = from.hours;
            let startMin = from.minutes;
            let endHour = to.hours;
            let endMin = to.minutes;

            // Get the date from the date picker
            let $row = $(fromTime).closest("tr");
            let dateInput = $row.find("input[name^='date']").val();
            let date = new Date(changeDateFormat(dateInput));
            const offset = date.getTimezoneOffset();
            date = new Date(date.getTime() - (offset * 60 * 1000));
            let string_day = date.toISOString().split('T')[0];
            let dayOfWeek = date.getDay();

            // Define time boundaries (24-hour format)
            const WORK_START = 9; // 9 AM
            const WORK_END = 17; // 5 PM
            const NIGHT_START = 20; // 8 PM
            const NIGHT_END = 6; // 6 AM

            // Check if it's weekend or holiday
            const isWeekendOrHoliday = dayOfWeek === 0 || dayOfWeek === 6 ||
                {!! json_encode($holiday_dates) !!}.includes(string_day);

            let totalMinutes = 0;

            // Calculate total duration in minutes
            let startTotal = startHour * 60 + startMin;
            let endTotal = endHour * 60 + endMin;

            // Handle overnight case
            if (endTotal < startTotal) {
                endTotal += 24 * 60; // Add 24 hours to end time
            }

            let duration = endTotal - startTotal;

            if (isWeekendOrHoliday) {
                // All hours at 2x
                totalMinutes = duration * 2;
            } else {
                // Split into time periods
                let current = startTotal;

                while (current < endTotal) {
                    let periodEnd;
                    let multiplier;

                    // Determine current period
                    if (current % (24 * 60) < NIGHT_END * 60) {
                        // Night period (12am-6am) - 2x
                        periodEnd = Math.min(endTotal, NIGHT_END * 60);
                        multiplier = 2;
                    } else if (current % (24 * 60) < WORK_START * 60) {
                        // Early morning (6am-9am) - 1.5x
                        periodEnd = Math.min(endTotal, WORK_START * 60);
                        multiplier = 1.5;
                    } else if (current % (24 * 60) < WORK_END * 60) {
                        // Working hours (9am-5pm) - excluded
                        periodEnd = Math.min(endTotal, WORK_END * 60);
                        multiplier = 0;
                    } else if (current % (24 * 60) < NIGHT_START * 60) {
                        // Evening (5pm-8pm) - 1.5x
                        periodEnd = Math.min(endTotal, NIGHT_START * 60);
                        multiplier = 1.5;
                    } else {
                        // Late night (8pm-12am) - 2x
                        periodEnd = Math.min(endTotal, 24 * 60);
                        multiplier = 2;
                    }

                    let periodMinutes = periodEnd - current;
                    if (periodMinutes > 0) {
                        totalMinutes += periodMinutes * multiplier;
                        current = periodEnd;
                    }

                    // Handle crossing midnight
                    if (current >= 24 * 60 && current < endTotal) {
                        current -= 24 * 60;
                        endTotal -= 24 * 60;
                    }
                }
            }

            // Convert to hours and minutes
            totalMinutes = Math.ceil(totalMinutes);
            let hours = Math.floor(totalMinutes / 60);
            let minutes = totalMinutes % 60;

            return (hours <= 9 ? "0" : "") + hours + ":" + (minutes <= 9 ? "0" : "") + minutes;
        }

    </script>


</x-sidebar>
