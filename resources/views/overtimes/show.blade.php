<x-sidebar>
    @section('title', __("Show Overtime Request"))
    <div class="m-4">
        <div class="mb-6">
            <a href="{{ url()->previous() }}">
                <button class="inline-block px-6 py-2.5 text-white font-medium text-xs leading-tight uppercase rounded-full shadow-md hover:bg-gray-300 hover:shadow-lg focus:bg-gray-300 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-gray-400 active:shadow-lg transition duration-150 ease-in-out blue-bg">
                    {{__("Back")}}
                </button>
            </a>
        </div>
        <div class="relative z-0 mb-6 w-full group">
            <input type="text" name="employee_id" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer" disabled value="{{ $overtime->employee->first_name }} {{ $overtime->employee->last_name }}" />
            <label for="employee_id" class="peer-focus:font-medium absolute text-sm duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6 blue-color">
                {{__("Employee")}}
            </label>
        </div>
        <div class="relative z-0 mb-6 w-full group">
            <input type=""text name="date" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer" disabled value="{{ $overtime->date }}" />
            <label for="date" class="peer-focus:font-medium absolute text-sm duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6 blue-color">
                {{__("Date")}}
            </label>
        </div>
        <div class="grid md:grid-cols-2 md:gap-6">
            <div class="relative z-0 mb-6 w-full group">
                <input type="text" name="from" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer" disabled value="{{ $overtime->from }}" />
                <label for="from" class="peer-focus:font-medium absolute text-sm duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6 blue-color">
                    {{__("From")}}
                </label>
            </div>
            <div class="relative z-0 mb-6 w-full group">
                <input type="text" name="to" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer" disabled value="{{ $overtime->to }}"/>
                <label for="to" class="peer-focus:font-medium absolute text-sm duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6 blue-color">
                    {{__("To")}}
                </label>
            </div>
        </div>
        <div class="relative z-0 mb-6 w-full group">
            <input type="text" name="hours" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer" disabled value="{{ $overtime->hours }}" />
            <label for="hours" class="peer-focus:font-medium absolute text-sm duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6 blue-color">
                {{__("Time Amount")}}
            </label>
        </div>
        @if($overtime->objective)
            <div class="relative z-0 mb-6 w-full group">
                <input type="text" name="objective" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer" disabled value="{{ $overtime->objective }}" />
                <label for="objective" class="peer-focus:font-medium absolute text-sm duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6 blue-color">
                    {{__("Objective")}}
                </label>
            </div>
        @endif
        <div class="relative z-0 mb-6 w-full group">
            <input type="date" name="date_of_submission" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer" disabled value="{{ $overtime->date_of_submission }}" />
            <label for="date_of_submission" class="peer-focus:font-medium absolute text-sm duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6 blue-color">
                {{__("Date of Submission")}}
            </label>
        </div>
        <div class="grid md:grid-cols-2 md:gap-6">
            <div class="relative z-0 mb-6 w-full group">
                @if($overtime->overtime_status == 0)
                    <input type="text" name="overtime_status" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer" disabled value="{{__("Pending")}}" />
                @elseif($overtime->overtime_status == 1)
                    <input type="text" name="overtime_status" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer" disabled value="{{__("Accepted")}}" />
                @else
                    <input type="text" name="overtime_status" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer" disabled value="{{__("Rejected")}}" />
                @endif
                <label for="overtime_status" class="peer-focus:font-medium absolute text-sm duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6 blue-color">
                    {{__("Status")}}
                </label>
            </div>
            @if($overtime->cancellation_reason)
                <div class="relative z-0 mb-6 w-full group">
                    <input type="text" name="cancellation_reason" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer" disabled value="{{ $overtime->cancellation_reason }}" />
                    <label for="cancellation_reason" class="peer-focus:font-medium absolute text-sm duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6 blue-color">
                        {{__("Rejection Reason")}}
                    </label>
                </div>
            @endif
        </div>
        @if(($overtime->overtime_status == 0) && (($overtime->processing_officer->name == "employee" && $overtime->employee->department->manager_id == auth()->user()->id) || ($overtime->processing_officer->name == "human_resource" && auth()->user()->hasRole('human_resource')) || ($overtime->processing_officer->name == "sg" && auth()->user()->hasRole(['sg', 'head']))))
            <button class="text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center" data-modal-toggle="acceptModal">
                {{__("Accept")}}
            </button>
            <button class="text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center" data-modal-toggle="rejectModal">
                {{__("Reject")}}
            </button>
        @endunless
    </div>


    <div id="rejectModal" tabindex="-1" aria-hidden="true"
         class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 w-full md:inset-0 h-modal md:h-full">
        <div class="relative p-4 w-full max-w-2xl h-full md:h-auto">
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow">
                <!-- Modal header -->
                <div
                    class="flex justify-between items-center p-4 rounded-t border-b">
                    <div
                        class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                        <svg @click="toggleModal" class="h-6 w-6 text-red-600"
                             xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                             stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                    <div class="text-base font-bold mt-3 sm:mt-0 sm:ml-4 sm:text-left">
                        {{__("Reject Overtime Request from")}}: {{ $overtime->employee->first_name }} {{ $overtime->employee->last_name }}
                    </div>
                    <div>
                        <button type="button"
                                class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center"
                                data-modal-toggle="rejectModal">
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
                <form method="POST"
                      action="{{ route('overtimes.reject', ['overtime' => $overtime->id]) }}">
                    @csrf
                    <div class="p-6 space-y-6">
                        <div class="text-base leading-relaxed text-gray-500">
                            {{__("Are you sure you want to reject this overtime request")}}? {{__("This action cannot be undone")}}.
                        </div>
                        <div class="text-base leading-relaxed text-gray-500">
                            <label for="cancellation_reason" class="text-lg text-gray-600">{{__("Rejection Reason")}}</label>
                            <textarea class="bg-gray-100 rounded border border-gray-400 leading-normal resize-none w-full h-20 py-2 px-3 font-medium placeholder-gray-700 focus:outline-none focus:bg-white rounded" name="cancellation_reason" ></textarea>
                        </div>
                    </div>
                    <!-- Modal footer -->
                    <div
                        class="flex justify-end items-center p-6 space-x-2 rounded-b border-t border-gray-200">
                        <div>
                            <button data-modal-toggle="rejectModal" type="button"
                                    class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10">
                                {{__("Cancel")}}
                            </button>
                        </div>
                        <div>
                            <button data-modal-toggle="rejectModal"
                                    class="text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                                {{__("Reject")}}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="acceptModal" tabindex="-1" aria-hidden="true"
         class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 w-full md:inset-0 h-modal md:h-full">
        <div class="relative p-4 w-full max-w-2xl h-full md:h-auto">
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow">
                <!-- Modal header -->
                <div
                    class="flex justify-between items-center p-4 rounded-t border-b">
                    <div
                        class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                        <svg @click="toggleModal" class="h-6 w-6 text-red-600"
                             xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                             stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                    <div class="text-base font-bold mt-3 sm:mt-0 sm:ml-4 sm:text-left">
                        {{__("Accept Overtime Request from")}}: {{ $overtime->employee->first_name }} {{ $overtime->employee->last_name }}
                    </div>
                    <div>
                        <button type="button"
                                class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center"
                                data-modal-toggle="acceptModal">
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
                        {{__("Are you sure you want to accept this overtime request")}}? {{__("This action cannot be undone")}}.
                    </div>
                </div>
                <!-- Modal footer -->
                <div
                    class="flex justify-end items-center p-6 space-x-2 rounded-b border-t border-gray-200">
                    <div>
                        <button data-modal-toggle="acceptModal" type="button"
                                class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10">
                            {{__("Cancel")}}
                        </button>
                    </div>
                    <div>
                        <form method="POST"
                              action="{{ route('overtimes.accept', ['overtime' => $overtime->id]) }}">
                            @csrf
                            <button data-modal-toggle="acceptModal"
                                    class="text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                                {{__("Accept")}}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-sidebar>>

