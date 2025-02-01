<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title') </title>

    <link rel="icon" type="image/x-icon" href="{{ asset('assets/images/favico32.png') }}">
    <script src="https://code.jquery.com/jquery-3.6.1.min.js"
        integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous"></script>
    <script src="https://cdn.tailwindcss.com/"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.13/flatpickr.min.js"
        integrity="sha512-K/oyQtMXpxI4+K0W7H25UopjM8pzq0yrVdFdG21Fh5dBe91I40pDd9A4lzNlHPHBIP2cwZuoxaUSX0GJSObvGA=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://npmcdn.com/flatpickr/dist/l10n/fr.js"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <link rel="stylesheet" href="https://unpkg.com/flowbite@1.5.3/dist/flowbite.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css"
        integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.13/flatpickr.min.css"
        integrity="sha512-MQXduO8IQnJVq1qmySpN87QQkiR1bZHtorbJBD0tzy7/0U9+YIC93QWHeGTEoojMVHWWNkoCp8V6OzVSYrX0oQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="{{ asset('vendor/megaphone/css/megaphone.css') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @stack('head')

    @livewireStyles
</head>

<body>
    <div>
        <div class="lg:flex h-full min-h-screen">
            <div class="lg:flex-column lg:w-1/6 border-blue-200 blue-bg-sidebar">
                <!-- <div class="logo-container w-full">
                    <img src="{{ asset('assets/images/logo-IFL.png') }}" alt="" />
                </div> -->
                <div class="flex flex-row items-center place-content-between">
                    <div class="lg:pt-6">
                        <button data-collapse-toggle="aside-default"
                            class="inline-flex items-center mx-2 p-2 text-sm text-white rounded-lg lg:hidden"
                            aria-controls="navbar-default" aria-expanded="false">
                            <span class="sr-only">Open main menu</span>
                            <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                    d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z"
                                    clip-rule="evenodd"></path>
                            </svg>
                        </button>
                    </div>
                </div>
                <div class="lg:mt-2">
                    <aside class="hidden w-full lg:inline blue-bg" style="margin-top: 1%;" id="aside-default">
                        <ul class="content-between space-y-2">
                            @unless (auth()->user()->hasExactRoles('employee'))
                                <li>
                                    <a class="flex items-center mx-2 px-2 py-2 text-white rounded-lg transition duration-75 group hover:bg-blue-500"
                                        href="{{ route('departments.index') }}">
                                        <span class="mx-2 font-medium">{{ __('Departments') }}</span>
                                    </a>

                                </li>
                            @endunless
                            @unless (auth()->user()->hasExactRoles('employee') && auth()->user()->is_supervisor == false)
                                <li>
                                    <a class="flex items-center mx-2 px-2 py-2 text-white rounded-lg transition duration-75 group hover:bg-blue-500"
                                        href="{{ route('employees.index') }}">
                                        <span class="mx-2 font-medium">{{ __('Users') }}</span>
                                    </a>
                                </li>
                            @endunless
                            @if (auth()->user()->can_submit_requests)
                                <li>
                                    <a href="{{ url(route('leaves.submitted')) }}"
                                        class="flex items-center mx-2 px-2 py-2 text-white rounded-lg transition duration-75 group hover:bg-blue-500">
                                        <span class="mx-2 font-medium">{{ __('Submitted Leave Requests') }}</span>
                                    </a>
                                </li>
                            @endif
                            @unless (auth()->user()->hasExactRoles('employee') && auth()->user()->is_supervisor == false)
                                <li>
                                    <button type="button"
                                        class="flex items-center mx-2 p-2 w-full text-base font-normal text-white rounded-lg transition duration-75 group hover:bg-blue-500"
                                        style="width: -webkit-fill-available;" aria-controls="dropdown-leaves"
                                        data-collapse-toggle="dropdown-leaves">
                                        <span class="flex-1 mx-2 text-left font-medium text-white"
                                            sidebar-toggle-item>{{ __('Received Leave Requests') }}</span>
                                        <svg sidebar-toggle-item class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd"
                                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                    </button>
                                    <ul id="dropdown-leaves" class="hidden py-2 space-y-2 mx-2">
                                        <li>
                                            <a href="{{ url(route('leaves.index')) }}"
                                                class="flex items-center p-2 pl-8 w-full text-base font-normal text-white rounded-lg transition duration-75 group hover:bg-blue-500">
                                                <span>{{ __('Incoming') }}</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ url(route('leaves.acceptedIndex')) }}"
                                                class="flex items-center p-2 pl-8 w-full text-base font-normal text-white rounded-lg transition duration-75 group hover:bg-blue-500">
                                                <span>{{ __('Accepted') }}</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ url(route('leaves.rejectedIndex')) }}"
                                                class="flex items-center p-2 pl-8 w-full text-base font-normal text-white rounded-lg transition duration-75 group hover:bg-blue-500">
                                                <span>{{ __('Rejected') }}</span>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                            @endunless
                            @if (auth()->user()->can_submit_requests)
                                <li>
                                    <a href="{{ url(route('overtimes.submitted')) }}"
                                        class="flex items-center mx-2 px-2 py-2 text-white rounded-lg transition duration-75 group hover:bg-blue-500">
                                        <span class="mx-2 font-medium">{{ __('Submitted Overtime Requests') }}</span>
                                    </a>
                                </li>
                            @endif
                            @unless (auth()->user()->hasExactRoles('employee') && auth()->user()->is_supervisor == false)
                                <li>
                                    <button type="button"
                                        class="flex items-center mx-2 p-2 w-full text-base font-normal text-white rounded-lg transition duration-75 group hover:bg-blue-500"
                                        style="width: -webkit-fill-available;" aria-controls="dropdown-overtimes"
                                        data-collapse-toggle="dropdown-overtimes">
                                        <span class="flex-1 mx-2 text-left font-medium text-white"
                                            sidebar-toggle-item>{{ __('Received Overtime Requests') }}</span>
                                        <svg sidebar-toggle-item class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd"
                                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                    </button>
                                    <ul id="dropdown-overtimes" class="hidden py-2 space-y-2 mx-2">
                                        <li>
                                            <a href="{{ url(route('overtimes.index')) }}"
                                                class="flex items-center p-2 pl-8 w-full text-base font-normal text-white rounded-lg transition duration-75 group hover:bg-blue-500">
                                                <span>{{ __('Incoming') }}</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ url(route('overtimes.acceptedIndex')) }}"
                                                class="flex items-center p-2 pl-8 w-full text-base font-normal text-white rounded-lg transition duration-75 group hover:bg-blue-500">
                                                <span>{{ __('Accepted') }}</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ url(route('overtimes.rejectedIndex')) }}"
                                                class="flex items-center p-2 pl-8 w-full text-base font-normal text-white rounded-lg transition duration-75 group hover:bg-blue-500">
                                                <span>{{ __('Rejected') }}</span>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                            @endunless
                            @if (auth()->user()->hasRole(['human_resource', 'sg', 'head']))
                                <li>
                                    <a class="flex items-center mx-2 px-2 py-2 text-white rounded-lg transition duration-75 group hover:bg-blue-500"
                                        href="{{ route('holidays.index') }}">
                                        <span class="mx-2 font-medium">{{ __('Configure Holidays') }}</span>
                                    </a>
                                </li>
                                <li>
                                    <a class="flex items-center mx-2 px-2 py-2 text-white rounded-lg transition duration-75 group hover:bg-blue-500"
                                        href="{{ route('blocked-days.index') }}">
                                        <span class="mx-2 font-medium">{{ __('Blocked Days') }}</span>
                                    </a>
                                </li>
                            @endif
                            @if (auth()->user()->is_supervisor ||
                                    auth()->user()->hasRole(['human_resource', 'sg', 'head']))
                                <li>
                                    <a class="flex items-center mx-2 px-2 py-2 text-white rounded-lg transition duration-75 group hover:bg-blue-500"
                                        href="{{ route('leaves.generateCalendar') }}">
                                        <span class="mx-2 font-medium">{{ __('Calendar') }}</span>
                                    </a>
                                </li>
                            @endif
                            @if (auth()->user()->hasRole('human_resource'))
                                <li>
                                    <a class="flex items-center mx-2 px-2 py-2 text-white rounded-lg transition duration-75 group hover:bg-blue-500"
                                        href="{{ route('notifications.tabbed_view') }}">
                                        <span class="mx-2 font-medium">{{ __('Notifications') }}</span>
                                    </a>
                                </li>
                            @endif
                            <li>
                                <a class="flex items-center mx-2 px-2 py-2 text-white rounded-lg transition duration-75 group hover:bg-blue-500"
                                    href="{{ route('employees.show', ['employee' => auth()->user()->id]) }}">
                                    <span class="mx-2 font-medium">{{ __('Show Profile') }}</span>
                                </a>
                            </li>
                            @if (auth()->user()->hasRole(['human_resource', 'sg', 'head']))
                                <li>
                                    <button type="button"
                                        class="flex items-center mx-2 p-2 w-full text-base font-normal text-white rounded-lg transition duration-75 group hover:bg-blue-500"
                                        style="width: -webkit-fill-available;" aria-controls="dropdown-reports"
                                        data-collapse-toggle="dropdown-reports">
                                        <span class="flex-1 mx-2 text-left whitespace-nowrap font-medium text-white"
                                            sidebar-toggle-item>{{ __('Reports') }}</span>
                                        <svg sidebar-toggle-item class="w-6 h-6" fill="currentColor"
                                            viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd"
                                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                    </button>
                                    <ul id="dropdown-reports" class="hidden py-2 space-y-2 mx-2">
                                        <li>
                                            <a href="{{ url(route('leaves.createReport')) }}"
                                                class="flex items-center p-2 pl-8 w-full text-base font-normal text-white rounded-lg transition duration-75 group hover:bg-blue-500">
                                                <span>{{ __('Leaves') }}</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ url(route('overtimes.createReport')) }}"
                                                class="flex items-center p-2 pl-8 w-full text-base font-normal text-white rounded-lg transition duration-75 group hover:bg-blue-500">
                                                <span>{{ __('Overtimes') }}</span>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                            @endif
                            <li>
                                <a class="flex items-center mx-2 px-2 py-2 text-white rounded-lg transition duration-75 group hover:bg-blue-500"
                                    href="{{ route('holidays-and-confessionnels.index') }}">
                                    <span class="mx-2 font-medium">{{ __('Holidays') }}</span>
                                </a>
                            </li>
                        </ul>
                    </aside>
                </div>
            </div>

            <div class="lg:pt-8 w-full h-full overflow-y-auto sm:pt-0 lg:mx-4 sm:mx-0">
                <nav class="w-full bg-white border-b-2 border-indigo-600 flex justify-between">
                    <div class="flex flex-col py-2">
                        <div class="px-2 text-xl font-bold text-black">
                            {{ auth()->user()->first_name }} {{ auth()->user()->last_name }}
                        </div>
                        <div class="px-2 text-md italic text-black">
                            {{ implode(' | ', auth()->user()->getRoleNamesCustom()) }}
                        </div>
                    </div>
                    <div class="flex mx-2">
                        <div class="flex justify-center items-center">
                            <livewire:megaphone></livewire:megaphone>
                        </div>

                        <div class="py-3 text-xl font-bold text-black">
                            <form method="POST" action="{{ route('employees.logout') }}">
                                @csrf
                                <button type="submit">
                                    <i class="fa-solid fa-right-from-bracket"></i> {{ __('Logout') }}
                                </button>
                            </form>
                        </div>
                    </div>
                </nav>
                @include('flash-messages.error-flash-message')
                <div>
                    {{ $slot }}
                </div>
            </div>
        </div>
    </div>
    <script src="https://unpkg.com/flowbite@1.5.3/dist/flowbite.js"></script>

    @livewireScripts
</body>

</html>
