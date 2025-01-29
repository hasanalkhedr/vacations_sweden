<x-sidebar>
    @section('title', __("Users"))
    <nav class="flex justify-between items-center p-2 text-black font-bold">
        <div class="text-lg blue-color">
            {{__("Users")}}
        </div>
        @hasanyrole('human_resource|sg|head')
        <div>
            <button class="hover:bg-blue-700 text-white py-2 px-4 rounded-full blue-bg"
                    data-modal-toggle="createModal">
                {{__("Create User")}}
            </button>
        </div>
        @endhasanyrole
    </nav>

    @include('partials.searches._search-employees')
    <div class="overflow-x-auto relative shadow-md sm:rounded-lg">
        <table x-data="data()" class="w-full text-sm text-left text-gray-500" x-data="employeeData">
            @unless($employees->isEmpty())
            <thead class="text-s text-gray-700 uppercase bg-gray-50">
            <tr>
                <th @click="sortByColumn" scope="col" class="cursor-pointer py-3 px-6 blue-color">
                    {{__('Name')}}
                </th>
                <th @click="sortByColumn" scope="col" class="cursor-pointer py-3 px-6 blue-color">
                    {{__('Department')}}
                </th>
                <th @click="sortByColumn" scope="col" class="cursor-pointer py-3 px-6 blue-color">
                    {{__("Role")}}
                </th>
                @if(auth()->user()->hasRole('human_resource'))
                    <th scope="col" class="py-3 px-6 blue-color">
                        <span class="sr-only">{{__("Edit")}}</span>
                    </th>
                    <th scope="col" class="py-3 px-6">
                        <span class="sr-only">{{__("Delete")}}</span>
                    </th>
                @endif
            </tr>
            </thead>
            <tbody x-ref="tbody">
                @foreach ($employees as $employee)
                    <tr class="bg-white hover:bg-gray-50">
                        <td class="border-b py-4 px-6 font-bold text-gray-900 whitespace-nowrap cursor-pointer" onclick="window.location.href = '{{ url(route('employees.show', ['employee' => $employee->id])) }}'">
                            <div class="cursor-pointer">
                                {{ $employee->first_name }} {{ $employee->last_name }}
                            </div>
                        </td>
                        @if($employee->department == NULL)
                            <td class="py-4 px-6 border-b">
                                <div class="font-bold">
                                    -
                                </div>
                            </td>
                        @else
                            <td class="py-4 px-6 border-b cursor-pointer" onclick="window.location.href = '{{ url(route('departments.show', ['department' => $employee->department->id])) }}'">
                                <div class="cursor-pointer">
                                    {{$employee->department->name}}
                                </div>
                            </td>
                        @endif
                        <td class="py-4 px-6 border-b">
                            {{(implode(' | ', $employee->getRoleNamesCustom())) }}
                        </td>
                        @hasanyrole('human_resource|sg|head')
                            <td class="py-4 px-6 text-right border-b">
                                <button class="font-medium hover:underline blue-color" type="button"
                                        data-modal-toggle="editProfileModal-{{$employee->id}}">
                                    {{__("Edit")}}
                                </button>
                            </td>
                            <td class="py-4 px-6 text-right border-b">
                                <button class="font-medium text-red-600 hover:underline" type="button"
                                        data-modal-toggle="deleteModal-{{$employee->id}}">
                                    {{__("Delete")}}
                                </button>
                            </td>
                        @endhasanyrole

                        <div id="deleteModal-{{$employee->id}}" tabindex="-1" aria-hidden="true"
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
                                            {{__("Delete Employee")}}: {{ $employee->first_name }} {{ $employee->last_name }}
                                        </div>
                                        <div>
                                            <button type="button"
                                                    class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center"
                                                    data-modal-toggle="deleteModal-{{$employee->id}}">
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
                                            {{__("Are you sure you want to delete this employee")}}? {{__("This action cannot
                                            be undone")}}.
                                        </div>
                                    </div>
                                    <!-- Modal footer -->
                                    <div
                                        class="flex justify-end items-center p-6 space-x-2 rounded-b border-t border-gray-200">
                                        <div>
                                            <button data-modal-toggle="deleteModal-{{$employee->id}}" type="button"
                                                    class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10">
                                                {{__("Cancel")}}
                                            </button>
                                        </div>
                                        <div>
                                            <form method="POST"
                                                  action="{{ route('employees.destroy', ['employee' => $employee->id]) }}">
                                                @csrf
                                                @method('DELETE')
                                                <button data-modal-toggle="deleteModal-{{$employee->id}}"
                                                        class="text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                                                    {{__("Delete")}}
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="editProfileModal-{{$employee->id}}" tabindex="-1" aria-hidden="true"
                             class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 w-full md:inset-0 h-modal md:h-full">
                            <div class="relative p-4 w-full max-w-2xl h-full md:h-auto">
                                <!-- Modal content -->
                                <div class="relative bg-white rounded-lg shadow">
                                    <!-- Modal header -->
                                    <div
                                        class="flex justify-between items-center p-4 rounded-t border-b">
                                        <div class="text-base font-bold mt-3 sm:mt-0 sm:ml-4 sm:text-left blue-color">
                                            {{__("Edit Employee")}}: {{ $employee->first_name }} {{ $employee->last_name }}
                                        </div>
                                        <div>
                                            <button type="button"
                                                    class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center"
                                                    data-modal-toggle="editProfileModal-{{$employee->id}}">
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
                                    <div class="p-6 overflow-y-auto" style="max-height: 500px">
                                        <form method="POST"
                                              action="{{ route('employees.updateProfile', ['employee' => $employee->id]) }}"
                                              id="edit_form--{{$employee->id}}"
                                                enctype="multipart/form-data">
                                            @csrf
                                            @method('PUT')
                                            <div class="grid md:grid-cols-2 md:gap-6">
                                                <div class="relative z-0 mb-4 w-full group">
                                                    <input type="text" name="first_name"
                                                           class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                                           value="{{$employee->first_name}}" required/>
                                                    <label for="first_name"
                                                           class="peer-focus:font-medium absolute text-sm duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6 blue-color">
                                                        {{__("First name")}}
                                                    </label>
                                                </div>
                                                <div class="relative z-0 mb-4 w-full group">
                                                    <input type="text" name="last_name"
                                                           class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                                           value="{{$employee->last_name}}" required/>
                                                    <label for="last_name"
                                                           class="peer-focus:font-medium absolute text-sm duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6 blue-color">
                                                        {{__("Last name")}}
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="grid md:grid-cols-2 md:gap-6">
                                                <div class="relative z-0 mb-4 w-full group">
                                                    <input type="email" name="email"
                                                           class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                                           value="{{$employee->email}}" required/>
                                                    <label for="email"
                                                           class="peer-focus:font-medium absolute text-sm duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6 blue-color">
                                                        {{__("Email Address")}}
                                                    </label>
                                                </div>
                                                <div class="relative z-0 mb-4 w-full group">
                                                    <input type="text" name="phone_number"
                                                           class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                                           value="{{$employee->phone_number}}"/>

{{--                                                    <input type="text" name="phone_number"--}}
{{--                                                           class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"--}}
{{--                                                           value="{{$employee->phone_number}}" required/>--}}
                                                    <label for="phone_number"
                                                           class="peer-focus:font-medium absolute text-sm duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6 blue-color">
                                                        {{__("Phone number")}}
                                                    </label>
                                                </div>
                                            </div>

                                            <div class="{{ $employee->can_submit_requests ? '' : 'hidden' }}" id="off-days-container--{{$employee->id}}">
                                                <div class="grid md:grid-cols-2 md:gap-6">
                                                    <div class="relative z-0 mb-4 w-full group">
                                                        <input type="number" name="nb_of_days"
                                                               class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                                               value="{{$employee->nb_of_days}}"
                                                               step="0.25" required/>
                                                        <label for="nb_of_days"
                                                               class="peer-focus:font-medium absolute text-sm duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6 blue-color">
                                                            {{__("Number of Days Off")}}
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="relative z-0 mb-4 w-full group">
                                                    <input type="number" name="overtime_minutes"
                                                           class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                                           placeholder=""
                                                           value="{{$employee->overtime_minutes}}"
                                                           required/>
                                                    <label for="overtime_minutes"
                                                           class="peer-focus:font-medium absolute text-sm duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6 blue-color">{{__("Overtime Minutes")}}</label>
                                                    @error('overtime_minutes')
                                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="relative z-40 mb-4 w-full group">
                                                <label for="role_ids"
                                                       class="mb-2 text-sm font-medium blue-color">
                                                    {{__("Select Role(s)")}}
                                                </label>
                                                <select id="role_ids--{{$employee->id}}" multiple name="role_ids[]" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                                        onchange="checkSupervisorRoles(this, {{$employee}})">
                                                    @if(count($roles))
                                                        @foreach($roles as $role)
                                                            @if($employee->hasRole($role->name))
                                                                <option selected value="{{ $role->id }}">{{__($role->display_name)}}</option>
                                                            @else
                                                                <option value="{{ $role->id }}">{{__($role->display_name)}}</option>
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                            <div class="relative z-0 mb-4 w-full group">
                                                <label for="department_id"
                                                       class="mb-2 text-sm font-medium blue-color">
                                                    {{__("Select Department")}}
                                                </label>
                                                <select id="department_id--{{$employee->id}}" name="department_id" id="department_id"
                                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                                        onchange="checkSupervisorDepartment(this, {{$employee}})">
                                                    <option value="" disabled>{{__("Select Department")}}</option>
                                                    @if(count($departments))
                                                        @foreach ($departments as $department)
                                                            <option
                                                                value="{{ $department->id }}" {{ ( $department->id == $employee->department_id) ? 'selected' : '' }}>{{ $department->name }}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                            @if($employee->department)
                                                <div class="hidden relative z-0 mb-4 w-full group" id="new_manager--{{$employee->id}}">
                                                    <label for="manager_id"
                                                           class="mb-2 italic text-sm font-medium text-red-900">
                                                        *{{__("Please assign a new Supérieure Hiérarchique for the department")}}*
                                                    </label>
                                                    <select name="manager_id"
                                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                                                        <option value="" disabled>{{__("Choose New Supérieure Hiérarchique")}}</option>
                                                        @if(count($employee->department->employees))
                                                            @foreach ($employee->department->employees as $department_employee)
                                                                @unless($department_employee->id == $employee->id)
                                                                    <option
                                                                        value={{ $department_employee->id }}>{{ $department_employee->first_name }} {{ $department_employee->last_name }}</option>
                                                                @endunless
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                </div>
                                            @endif

                                            <div class="grid md:grid-cols-2 md:gap-6">
                                                <div class="relative z-0 mb-6 w-full group">
                                                    <p class="mb-2 text-sm font-medium blue-color">{{__("Submit Requests")}}</p>
                                                    <div class="mt-2 flex flex-row">
                                                        <input type="checkbox" name="can_submit_requests" id="can-submit-requests--{{$employee->id}}" {{ $employee->can_submit_requests ? 'checked' : '' }} onchange="toggleOffDaysContainer(this)">
                                                    </div>
                                                    @error('can_submit_requests')
                                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                                    @enderror
                                                </div>

                                                <div class="relative z-0 mb-6 w-full group">
                                                    <p class="mb-2 text-sm font-medium blue-color">{{__("Receive Emails")}}</p>
                                                    <div class="mt-2 flex flex-row">
                                                        <input type="checkbox" name="can_receive_emails" {{ $employee->can_receive_emails ? 'checked' : '' }}>
                                                    </div>
                                                    @error('can_receive_emails')
                                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="relative z-0 mb-6 w-full group">
                                                <p class="mb-2 text-sm font-medium blue-color">{{__("Weekdays off")}}</p>
                                                <div class="weekday-selector">
                                                    <div class="weekday-checkboxes">
                                                        <div class="weekday-container text-gray-900 text-sm">
                                                            <input type="checkbox" id="weekday-mon" name="weekdays_off[]" value=1 {{ in_array(1, $employee->weekdays_off) ? 'checked' : ''}}>
                                                            <label for="weekday-mon">{{__("Mon")}}</label>
                                                        </div>
                                                        <div class="weekday-container text-gray-900 text-sm">
                                                            <input type="checkbox" id="weekday-tue" name="weekdays_off[]" value=2 {{ in_array(2, $employee->weekdays_off) ? 'checked' : ''}}>
                                                            <label for="weekday-tue">{{__("Tue")}}</label>
                                                        </div>
                                                        <div class="weekday-container text-gray-900 text-sm">
                                                            <input type="checkbox" id="weekday-wed" name="weekdays_off[]" value=3 {{ in_array(3, $employee->weekdays_off) ? 'checked' : ''}}>
                                                            <label for="weekday-wed">{{__("Wed")}}</label>
                                                        </div>
                                                        <div class="weekday-container text-gray-900 text-sm">
                                                            <input type="checkbox" id="weekday-thu" name="weekdays_off[]" value=4 {{ in_array(4, $employee->weekdays_off) ? 'checked' : ''}}>
                                                            <label for="weekday-thu">{{__("Thu")}}</label>
                                                        </div>
                                                        <div class="weekday-container text-gray-900 text-sm">
                                                            <input type="checkbox" id="weekday-fri" name="weekdays_off[]" value=5 {{ in_array(5, $employee->weekdays_off) ? 'checked' : ''}}>
                                                            <label for="weekday-fri">{{__("Fri")}}</label>
                                                        </div>
                                                        <div class="weekday-container text-gray-900 text-sm">
                                                            <input type="checkbox" id="weekday-sat" name="weekdays_off[]" value=6 {{ in_array(6, $employee->weekdays_off) ? 'checked' : ''}}>
                                                            <label for="weekday-sat">{{__("Sat")}}</label>
                                                        </div>
                                                        <div class="weekday-container text-gray-900 text-sm">
                                                            <input type="checkbox" id="weekday-sun" name="weekdays_off[]" value=7 {{ in_array(7, $employee->weekdays_off) ? 'checked' : ''}}>
                                                            <label for="weekday-sun">{{__("Sun")}}</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div>
                                                <div class="relative z-0 mb-4 w-full group">
                                                    <label for="profile_photo" class="mb-2 text-sm font-medium blue-color">
                                                        {{ __('Profile Photo') }}
                                                    </label>
                                                    <div class="flex w-full">
                                                        <label class="px-2 w-max flex flex-col items-center px py-2 bg-white text-white rounded-lg shadow-lg tracking-wide uppercase border border-blue cursor-pointer blue-bg">
                                                            <svg class="w-5 h-5" fill="white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                                                <path d="M16.88 9.1A4 4 0 0 1 16 17H5a5 5 0 0 1-1-9.9V7a3 3 0 0 1 4.52-2.59A4.98 4.98 0 0 1 17 8c0 .38-.04.74-.12 1.1zM11 11h3l-4-4-4 4h3v3h2v-3z" />
                                                            </svg>
                                                            <span class="text-xs text-white leading-normal">{{ __("Choose File") }}</span>
                                                            <input class="hidden" type="file" name="profile_photo" id="profile_image_input--{{$employee->id}}" onchange="readUrl(this, {{$employee->id}})" />
                                                        </label>
                                                    </div>
{{--                                                    <input type="file" name="profile_photo" id="profile_image_input--{{$employee->id}}" onchange="readUrl(this, {{$employee->id}})" style="color: rgba(0, 0, 0, 0);">--}}
                                                    @error('profile_photo')
                                                    <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <div class="relative z-0 mb-4 group align-center inline-block {{ !$employee->profile_photo ? 'hidden' : ''}}" id="profile_image_preview--{{$employee->id}}">
                                                    <img id="preview-image-before-upload--{{$employee->id}}" src={{ $employee->profile_photo ? asset('storage/' . $employee->profile_photo) : "" }}
                                                         alt="{{__("Profile Photo")}}" style="max-height: 250px; display: inline">
                                                    <span class="close hover:cursor-pointer text-2xl" style="position: absolute; right: -20px; z-index: 100;" onclick="removeImage({{$employee->id}})">&times;</span>
                                                </div>
                                                <input name="is_deleted" hidden type="number" value="0" id="deleted_photo--{{$employee->id}}"/>
                                            </div>

                                            <div
                                                class="flex justify-end items-center p-6 space-x-2 rounded-b border-t border-gray-200">
                                                <div>
                                                    <button data-modal-toggle="editProfileModal-{{$employee->id}}"
                                                            type="button"
                                                            class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10">
                                                        {{__("Cancel")}}
                                                    </button>
                                                </div>
                                                <div>
                                                    <button
                                                        class="text-white hover:bg-blue-400 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center blue-bg"
                                                        data-modal-toggle="editProfileModal-{{$employee->id}}">{{__("Edit")}}
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
                        <p class="text-center">{{__("No Employees Found")}}</p>
                    </td>
                </tr>
            @endunless
            </tbody>
        </table>

        <div id="createModal" tabindex="-1" aria-hidden="true"
             class="hidden overflow-y-auto overflow-x-auto fixed top-0 right-0 left-0 z-50 w-full md:inset-0 h-modal md:h-full">
            <div class="relative p-4 w-full mt-40 max-w-2xl h-full md:h-auto">
                <!-- Modal content -->
                <div class="relative bg-white rounded-lg shadow p-2">
                    <!-- Modal header -->
                    <div class="flex justify-between items-center p-2 rounded-t border-b">
                        <div class="text-base font-bold mt-3 sm:mt-0 sm:ml-4 sm:text-left blue-color">
                            {{__("Create User")}}
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
                    <div class="p-4 overflow-y-auto"  style="max-height: 700px">
                        <form method="POST" action="{{ route('employees.store') }}" enctype="multipart/form-data">
                            @csrf
                            <div class="grid md:grid-cols-2 md:gap-6">
                                <div class="relative z-0 mb-4 w-full group">
                                    <input type="text" name="first_name"
                                           class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                           placeholder="" required/>
                                    <label for="first_name"
                                           class="peer-focus:font-medium absolute text-sm duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6 blue-color">
                                        {{__("First name")}}
                                    </label>
                                    @error('first_name')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="relative z-0 mb-4 w-full group">
                                    <input type="text" name="last_name"
                                           class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                           placeholder="" required/>
                                    <label for="last_name"
                                           class="peer-focus:font-medium absolute text-sm duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6 blue-color">
                                        {{__("Last name")}}
                                    </label>
                                    @error('last_name')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <div class="relative z-0 mb-4 w-full group">
                                <input type="email" name="email"
                                       class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                       placeholder="" required/>
                                <label for="email"
                                       class="peer-focus:font-medium absolute text-sm duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6 blue-color">{{__("Email Address")}}</label>
                                @error('email')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="relative z-0 mb-4 w-full group">
                                <input type="password" name="password"
                                       class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                       placeholder="" required/>
                                <label for="password"
                                       class="peer-focus:font-medium absolute text-sm duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6 blue-color">{{__("Password")}}</label>
                                @error('password')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="relative z-0 mb-4 w-full group">
                                <input type="password" name="password_confirmation"
                                       class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                       placeholder="" required/>
                                <label for="password_confirmation"
                                       class="peer-focus:font-medium absolute text-sm duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6 blue-color">
                                    {{__("Confirm Password")}}
                                </label>
                                @error('password_confirmation')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="relative z-0 mb-4 w-full group">
                                <input type="text" name="phone_number"
                                       class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                       placeholder="" />
                                <label for="phone_number"
                                       class="peer-focus:font-medium absolute text-sm duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6 blue-color">
                                    {{__("Phone number")}}
                                </label>
                                @error('phone_number')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="hidden"  id="off-days-container--0">
                                <div class="grid md:grid-cols-2 md:gap-6">
                                    <div class="relative z-0 mb-4 w-full group">
                                        <input type="number" name="nb_of_days"
                                               class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                               placeholder=""
                                               value=30
                                               step=any required/>
                                        <label for="nb_of_days"
                                               class="peer-focus:font-medium absolute text-sm duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6 blue-color">
                                            {{__("Number of Days Off")}}
                                        </label>
                                        @error('nb_of_days')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="relative z-0 mb-4 w-full group">
                                    <input type="number" name="overtime_minutes"
                                           class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                           placeholder=""
                                           value=0
                                           required/>
                                    <label for="overtime_minutes"
                                           class="peer-focus:font-medium absolute text-sm duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6 blue-color">{{__("Overtime Minutes")}}</label>
                                    @error('overtime_minutes')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <div class="relative z-40 mb-4 w-full group">
                                <label for="role_ids"
                                       class="mb-2 text-sm font-medium blue-color">
                                    {{__("Select Role(s)")}}
                                </label>
                                <select multiple name="role_ids[]" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">

                                @if(count($roles))
                                        @foreach($roles as $role)
                                        <option value="{{ $role->id }}">{{__($role->display_name)}}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="relative z-0 mb-4 w-full group">
                                <label for="department_id"
                                       class="mb-2 text-sm font-medium blue-color">
                                    {{__("Select Department")}}
                                </label>
                                <select name="department_id" id="department_id_create"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                                    <option value="" disabled>{{__("Select Department")}}</option>
                                    @if(count($departments))
                                        @foreach ($departments as $department)
                                            <option value="{{ $department->id }}">{{ $department->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>

                            <div class="grid md:grid-cols-2 md:gap-6">
                                <div class="relative z-0 mb-4 w-full group">
                                    <p class="mb-2 text-sm font-medium blue-color">{{__("Submit Requests")}}</p>
                                    <div class="mt-2 flex flex-row">
                                        <input type="checkbox" name="can_submit_requests" id="can-submit-requests--0"  onchange="toggleOffDaysContainer(this)">
                                    </div>
                                    @error('can_submit_requests')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="relative z-0 mb-4 w-full group">
                                    <p class="mb-2 text-sm font-medium blue-color">{{__("Receive Emails")}}</p>
                                    <div class="mt-2 flex flex-row">
                                        <input type="checkbox" name="can_receive_emails" id="can_receive_emails">
                                    </div>
                                    @error('can_receive_emails')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="relative z-0 mb-6 w-full group">
                                <p class="mb-2 text-sm font-medium blue-color">{{__("Weekdays off")}}</p>
                                <div class="weekday-selector">
                                    <div class="weekday-checkboxes">
                                    <div class="weekday-container text-gray-900 text-sm">
                                        <input type="checkbox" id="weekday-mon" name="weekdays_off[]" value=1>
                                        <label for="weekday-mon">{{__("Mon")}}</label>
                                    </div>
                                    <div class="weekday-container text-gray-900 text-sm">
                                        <input type="checkbox" id="weekday-tue" name="weekdays_off[]" value=2>
                                        <label for="weekday-tue">{{__("Tue")}}</label>
                                    </div>
                                    <div class="weekday-container text-gray-900 text-sm">
                                        <input type="checkbox" id="weekday-wed" name="weekdays_off[]" value=3>
                                        <label for="weekday-wed">{{__("Wed")}}</label>
                                    </div>
                                    <div class="weekday-container text-gray-900 text-sm">
                                        <input type="checkbox" id="weekday-thu" name="weekdays_off[]" value=4>
                                        <label for="weekday-thu">{{__("Thu")}}</label>
                                    </div>
                                    <div class="weekday-container text-gray-900 text-sm">
                                        <input type="checkbox" id="weekday-fri" name="weekdays_off[]" value=5>
                                        <label for="weekday-fri">{{__("Fri")}}</label>
                                    </div>
                                    <div class="weekday-container text-gray-900 text-sm">
                                        <input type="checkbox" id="weekday-sat" name="weekdays_off[]" value=6 checked>
                                        <label for="weekday-sat">{{__("Sat")}}</label>
                                    </div>
                                    <div class="weekday-container text-gray-900 text-sm">
                                        <input type="checkbox" id="weekday-sun" name="weekdays_off[]" value=7 checked>
                                        <label for="weekday-sun">{{__("Sun")}}</label>
                                    </div>
                                </div>
                                </div>
                            </div>

                            <div>
                                <div class="relative z-0 mb-4 w-full group flex flex-col">
                                    <label for="profile_photo" class="mb-2 text-sm font-medium blue-color">
                                        {{ __('Profile Photo') }}
                                    </label>
                                    <div class="flex w-full">
                                        <label class="px-2 w-max flex flex-col items-center px py-2 bg-white text-white rounded-lg shadow-lg tracking-wide uppercase border border-blue cursor-pointer blue-bg">
                                            <svg class="w-5 h-5" fill="white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                                <path d="M16.88 9.1A4 4 0 0 1 16 17H5a5 5 0 0 1-1-9.9V7a3 3 0 0 1 4.52-2.59A4.98 4.98 0 0 1 17 8c0 .38-.04.74-.12 1.1zM11 11h3l-4-4-4 4h3v3h2v-3z" />
                                            </svg>
                                            <span class="text-xs text-white leading-normal">{{ __("Choose File") }}</span>
                                            <input class="hidden" type="file" name="profile_photo" id="profile_image_input--0" onchange="readUrl(this, 0)"
                                                   accept=".jpg, .png, .jpeg, .svg"/>
                                        </label>
                                    </div>
                                    @error('profile_photo')
                                    <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="relative z-0 mb-4 group align-center hidden inline-block" id="profile_image_preview--0">
                                    <img id="preview-image-before-upload--0" src=""
                                         alt="{{__("Profile Photo")}}" style="max-height: 250px; display: inline">
                                    <span class="close hover:cursor-pointer text-2xl" style="position: absolute; right: -20px; z-index: 100;" onclick="removeImage(0)">&times;</span>
                                </div>
                            </div>

                            <div
                                class="flex justify-end items-center p-6 space-x-2 rounded-b border-t border-gray-200">
                                <div>
                                    <button data-modal-toggle="createModal" type="button"
                                            class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10">
                                        {{__("Cancel")}}
                                    </button>
                                </div>
                                <div>
                                    <button
                                        class="text-white hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center blue-bg">
                                        {{__("Create")}}
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
        {{ $employees->links() }}
    </div>

    <script type="text/javascript">
        function enableOrDisableDepartment(that) {
            let select = document.getElementById('role_id');
            let role = select.options[select.selectedIndex].text;
            if (role == "employee") {
                document.getElementById("department_id").disabled = false;
            } else {
                document.getElementById("department_id").disabled = true;
            }
        }
    </script>
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
                            !isNaN(row2)
                                ? row1 - row2
                                : row1.toString().localeCompare(row2);
                        })(
                            this.getCellValue(this.sortAsc ? a : b, index),
                            this.getCellValue(this.sortAsc ? b : a, index)
                        );
                }
            };
        }

    </script>

    <script type="text/javascript">
        let role_ids_names_pairs = {};
        {!! $roles !!}.forEach((role) => {
            role_ids_names_pairs[role.id] = role.name;
        })
        function checkSupervisorRoles(that, employee) {
            employee_id= $(that)[0].id.split('--')[1]
            let role_ids = $(that).val()
            let employee_role_id = Object.keys(role_ids_names_pairs).find(key => role_ids_names_pairs[key] === 'employee')
            if(!role_ids.includes(employee_role_id) && employee.id == employee.department.manager_id) {
                $('#new_manager--' + employee_id)[0].classList.remove('hidden')
            }
            else {
                if(!$('#new_manager--' + employee_id)[0].classList.contains('hidden'))
                    $('#new_manager--' + employee_id)[0].classList.add('hidden')
            }
        }

        function checkSupervisorDepartment(that, employee) {
            console.log(employee);
            let department_id = employee.department_id
            employee_id= $(that)[0].id.split('--')[1]
            let selected_department_id = $(that).val()
            if(department_id != selected_department_id && employee.id == employee.department.manager_id) {
                $('#new_manager--' + employee_id)[0].classList.remove('hidden')
            }
            else {
                if(!$('#new_manager--' + employee_id)[0].classList.contains('hidden'))
                    $('#new_manager--' + employee_id)[0].classList.add('hidden')
            }
        }

        function readUrl(file_input, id){
            if(file_input.value) {
                uploadImage(file_input, id)
            }
            else {
                removeImage()
            }
        };

        function uploadImage(file_input, id) {
            let profile_image_preview = document.getElementById('profile_image_preview--' + id);
            profile_image_preview.classList.remove('hidden');
            let reader = new FileReader();

            reader.onload = (e) => {

                $('#preview-image-before-upload--' + id).attr('src', e.target.result);
            }

            reader.readAsDataURL(file_input.files[0]);
            $('#deleted_photo--' + id)[0].value = 0
        }
        function removeImage(id) {
            let profile_image_preview = document.getElementById('profile_image_preview--' + id);
            $('#preview-image-before-upload--' + id).attr('src', "");
            profile_image_preview.classList.add('hidden');
            $('#profile_image_input--' + id)[0].value = ""
            $('#deleted_photo--' + id)[0].value = 1
        }

        function toggleOffDaysContainer(value) {
            let employeeID = value.id.split('--')[1];
            let offDaysContainer = document.getElementById('off-days-container--' + employeeID);
            offDaysContainer.classList.toggle('hidden')
        }

    </script>
</x-sidebar>
