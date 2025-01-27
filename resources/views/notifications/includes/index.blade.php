<div class="px-4 overflow-x-auto relative shadow-md sm:rounded-lg">
    <table class="w-full text-sm text-left text-gray-500">
        @unless($notifications->isEmpty())
            <thead class="text-s blue-color uppercase bg-gray-50">
            <tr>
                <th scope="col" class="cursor-pointer py-3 px-6">
                    {{ __('Title') }}
                </th>
                <th scope="col" class="cursor-pointer py-3 px-6">
                    {{ __('Date') }}
                </th>
                <th scope="col" class="py-3 px-6">
                    <span class="sr-only">{{ __('Remove') }}</span>
                </th>
            </tr>
            </thead>
            <tbody>
            @foreach ($notifications as $notification)
                <tr class="bg-white">
                    <td class="border-b py-4 px-6 font-bold text-gray-900 whitespace-nowrap cursor-pointer"
                        onclick="window.location.href = '{{ url(route('notifications.show', ['notificationsGroup' => $notification->id, 'current_page' => $notifications->currentPage()])) }}'">
                        {{ $notification->title }}
                    </td>
                    <td class="py-4 px-6 border-b cursor-pointer"
                        onclick="window.location.href = '{{ url(route('notifications.show', ['notificationsGroup' => $notification->id,  'current_page' => $notifications->currentPage()])) }}'">
                        {{ $notification->created_at->format(config('app.date_format')) }}
                    </td>
                    <td class="py-4 px-6 border-b cursor-pointer text-right">
                        <button class="font-medium text-red-600 hover:underline" type="button"
                                data-modal-toggle="deleteModal-{{ $notification->id }}">
                            {{ __('Remove') }}
                        </button>
                    </td>
                </tr>
                <div id="deleteModal-{{$notification->id}}" tabindex="-1" aria-hidden="true"
                     class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 w-full md:inset-0 h-modal md:h-full">
                    <div class="relative p-4 w-full max-w-2xl h-full md:h-auto">
                        <!-- Modal content -->
                        <div class="relative bg-white rounded-lg shadow">
                            <!-- Modal header -->
                            <div
                                class="flex justify-between items-center p-4 rounded-t border-b ">
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
                                    {{__("Delete Notification")}}: {{ $notification->title }}
                                </div>
                                <div>
                                    <button type="button"
                                            class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center"
                                            data-modal-toggle="deleteModal-{{$notification->id}}">
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
                                    {{__("Are you sure you want to delete this notification")}}? {{__("This action cannot be undone")}}.
                                </div>
                            </div>
                            <!-- Modal footer -->
                            <div
                                class="flex justify-end items-center p-6 space-x-2 rounded-b border-t border-gray-200">
                                <div>
                                    <button data-modal-toggle="deleteModal-{{$notification->id}}" type="button"
                                            class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10">
                                        {{__("Cancel")}}
                                    </button>
                                </div>
                                <div>
                                    <form method="POST"
                                          action="{{ route('notifications.destroy', ['notificationsGroup' => $notification->id]) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button data-modal-toggle="deleteModal-{{$notification->id}}"
                                                class="text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                                            {{__("Delete")}}
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            @endforeach
            @else
                <tr class="border-gray-300">
                    <td colspan="4" class="px-4 py-8 border-t border-gray-300 text-lg">
                        <p class="text-center">{{ __('No Notifications Found') }}</p>
                    </td>
                </tr>
            @endunless
            </tbody>
    </table>
</div>

<div class="mt-6 p-4">
    {{ $notifications->appends(['active_tab' => 'notifications'])->links() }}
</div>
