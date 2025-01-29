<x-sidebar>
    @section('title', __('Submitted Leave Requests'))
    @push('head')
        <script src="https://unpkg.com/flowbite@1.5.3/dist/datepicker.js"></script>
    @endpush
    <nav class="flex justify-between items-center p-2 font-bold blue-color">
        <div class="text-lg">
            {{ __('Submitted Leave Requests') }}
        </div>
        <div>
            <a href="{{ url(route('leaves.create')) }}">
                <button class="hover:bg-blue-400 text-white py-2 px-4 rounded-full blue-bg">
                    {{ __('Submit Leave Request') }}
                </button>
            </a>
        </div>
    </nav>
    <div class="rounded-lg p-4 overflow-x-auto relative shadow-md sm:rounded-lg">
        <table x-data="data()"
            class="rounded-lg border-collapse border border-slate-200 w-full text-sm text-left text-gray-500"
            x-data="leaveData">
            @unless($leaves->isEmpty())
                <thead class="text-s uppercase bg-gray-50 blue-color">
                    <tr>
                        <th @click="sortByColumn" scope="col" class="cursor-pointer py-3 px-6">
                            {{ __('From') }}
                        </th>
                        <th @click="sortByColumn" scope="col" class="cursor-pointer py-3 px-6">
                            {{ __('To') }}
                        </th>
                        <th @click="sortByColumn" scope="col" class="cursor-pointer py-3 px-6">
                            {{ __('Status') }}
                        </th>
                        <th @click="sortByColumn" scope="col" class="cursor-pointer py-3 px-6">
                            {{ __('Processing Officer') }}
                        </th>
                        <th scope="col" class="py-3 px-6">
                            <span class="sr-only">{{ __('Remove') }}</span>
                        </th>
                    </tr>
                </thead>
                <tbody x-ref="tbody">
                    @foreach ($leaves as $leave)
                        <tr class="bg-white">
                            <td class="py-4 px-6 cursor-pointer"
                                onclick="window.location.href = '{{ url(route('leaves.show', ['leave' => $leave->id])) }}'">
                                {{ $leave->from }}
                            </td>
                            <td class="py-4 px-6 cursor-pointer"
                                onclick="window.location.href = '{{ url(route('leaves.show', ['leave' => $leave->id])) }}'">
                                {{ $leave->to }}
                            </td>
                            <td class="py-4 px-6 cursor-pointer"
                                onclick="window.location.href = '{{ url(route('leaves.show', ['leave' => $leave->id])) }}'">
                                @if ($leave->leave_status == 0)
                                    {{ __('Pending') }}
                                @elseif($leave->leave_status == 1)
                                    <div class="text-green-500">
                                        {{ __('Accepted') }}
                                    </div>
                                @else
                                    <div class="text-red-500">
                                        {{ __('Rejected') }}
                                    </div>
                                @endif
                            </td>
                            <td class="py-4 px-6 cursor-pointer"
                                onclick="window.location.href = '{{ url(route('leaves.show', ['leave' => $leave->id])) }}'">
                                @if ($leave->processing_officer->name == 'employee')
                                    {{ __('Supérieure Hiérarchique') }}
                                @else
                                    {{__($leave->processing_officer->display_name) }}
                                @endif

                            </td>
                            @if ($leave->leave_status == 0)
                                <td class="py-4 px-6 text-right">
                                    <button class="font-medium text-red-600 hover:underline" type="button"
                                        data-modal-toggle="deleteModal-{{ $leave->id }}">
                                        {{ __('Remove') }}
                                    </button>
                                </td>
                            @endif

                            <div id="deleteModal-{{ $leave->id }}" tabindex="-1" aria-hidden="true"
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
                                                {{ __('Delete Leave Request') }}
                                            </div>
                                            <div>
                                                <button type="button"
                                                    class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center"
                                                    data-modal-toggle="deleteModal-{{ $leave->id }}">
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
                                                {{ __('Are you sure you want to delete this leave request') }}?
                                                {{ __("This action
                                                                                            cannot be undone") }}.
                                            </div>
                                        </div>
                                        <!-- Modal footer -->
                                        <div
                                            class="flex justify-end items-center p-6 space-x-2 rounded-b border-t border-gray-200">
                                            <div>
                                                <button data-modal-toggle="deleteModal-{{ $leave->id }}" type="button"
                                                    class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10">
                                                    {{ __('Cancel') }}
                                                </button>
                                            </div>
                                            <div>
                                                <form method="POST"
                                                    action="{{ route('leaves.destroy', ['leave' => $leave->id]) }}">
                                                    @csrf
                                                    <button data-modal-toggle="deleteModal-{{ $leave->id }}"
                                                        class="text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                                                        {{ __('Delete') }}
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </tr>
                    @endforeach
                @else
                    <tr class="border-gray-300">
                        <td colspan="4" class="px-4 py-8 border-t border-gray-300 text-lg">
                            <p class="text-center">{{ __('No Submitted Leave Requests Found') }}</p>
                        </td>
                    </tr>
                @endunless
            </tbody>
        </table>

    </div>
    <div class="mt-6 p-4">
        {{ $leaves->links() }}
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
