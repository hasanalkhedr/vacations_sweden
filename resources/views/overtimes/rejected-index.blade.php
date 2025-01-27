<x-sidebar>
    @section('title', __('Rejected Overtime Requests'))
    @push('head')
        <script src="https://unpkg.com/flowbite@1.5.3/dist/datepicker.js"></script>
    @endpush
    <nav class="flex justify-between items-center p-2 font-bold blue-color">
        <div class="text-lg">
            {{ __('Rejected Overtime Requests') }}
        </div>
    </nav>
    @include('partials.searches._search-rejected-overtimes')
    <div class="rounded-lg p-4 overflow-x-auto relative shadow-md sm:rounded-lg">
        <table x-data="data()"
            class="rounded-lg border-collapse border border-slate-200 w-full text-sm text-left text-gray-500"
            x-data="overtimeData">
            @unless($overtimes->isEmpty())
                <thead class="text-s uppercase bg-gray-50 blue-color">
                    <tr>
                        <th @click="sortByColumn" scope="col" class="cursor-pointer py-3 px-6">
                            {{ __('Employee') }}
                        </th>
                        <th @click="sortByColumn" scope="col" class="cursor-pointer py-3 px-6">
                            {{ __('Date') }}
                        </th>
                        <th @click="sortByColumn" scope="col" class="cursor-pointer py-3 px-6">
                            {{ __('Hours') }}
                        </th>
                        <th @click="sortByColumn" scope="col" class="cursor-pointer py-3 px-6">
                            {{ __('Status') }}
                        </th>
                    </tr>
                </thead>
                <tbody x-ref="tbody">
                    @foreach ($overtimes as $overtime)
                        <tr class="bg-white hover:bg-gray-50">
                            <td class="py-4 px-6 cursor-pointer"
                                onclick="window.location.href = '{{ url(route('overtimes.show', ['overtime' => $overtime->id])) }}'">
                                {{ $overtime->employee->first_name }} {{ $overtime->employee->last_name }}
                            </td>
                            <td class="py-4 px-6 cursor-pointer"
                                onclick="window.location.href = '{{ url(route('overtimes.show', ['overtime' => $overtime->id])) }}'">
                                {{ $overtime->date }}
                            </td>
                            <td class="py-4 px-6 cursor-pointer"
                                onclick="window.location.href = '{{ url(route('overtimes.show', ['overtime' => $overtime->id])) }}'">
                                {{ $overtime->hours }}
                            </td>
                            <td class="py-4 px-6 cursor-pointer"
                                onclick="window.location.href = '{{ url(route('overtimes.show', ['overtime' => $overtime->id])) }}'">
                                @if ($overtime->overtime_status == 0)
                                    {{ __('Pending') }}
                                @elseif($overtime->overtime_status == 1)
                                    <div class="text-green-500">
                                        {{ __('Accepted') }}
                                    </div>
                                @else
                                    <div class="text-red-500">
                                        {{ __('Rejected') }}
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr class="border-gray-300">
                        <td colspan="4" class="px-4 py-8 border-t border-gray-300 text-lg">
                            <p class="text-center">{{ __('No Rejected Overtime Requests Found') }}</p>
                        </td>
                    </tr>
                @endunless
            </tbody>
        </table>
    </div>

    <div class="mt-6 p-4">
        {{ $overtimes->links() }}
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

</x-sidebar>
