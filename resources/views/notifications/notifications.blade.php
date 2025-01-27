<x-sidebar>
    @section('title', __("Notifications"))
    <div class="mb-4 border-b border-gray-200 dark:border-gray-700">
        <ul class="flex flex-wrap -mb-px text-sm font-medium text-center" id="myTab" data-tabs-toggle="#myTabContent" role="tablist">
            <li class="mr-2" role="presentation">
                <button id="holidaysButton"
                        class="inline-block p-4 border-b-2 rounded-t-lg {{$activeTab == "create" ? 'text-blue-600 hover:text-blue-600 dark:text-blue-500 dark:hover:text-blue-500 border-blue-600 dark:border-blue-500' : 'dark:border-transparent text-gray-500 hover:text-gray-600 dark:text-gray-400 border-gray-100 hover:border-gray-300 dark:border-gray-700 dark:hover:text-gray-300'}}"
                        id="create-tab" data-tabs-target="#create" type="button" role="tab" aria-controls="create" aria-selected="{{ $activeTab == "create" ? 'true' : 'false' }}">
                    {{__("Send Message")}}</button>
            </li>
            <li class="mr-2" role="presentation">
                <button id="confessionnelsButton"
                        class="inline-block p-4 border-b-2 rounded-t-lg {{$activeTab == "notifications" ? 'text-blue-600 hover:text-blue-600 dark:text-blue-500 dark:hover:text-blue-500 border-blue-600 dark:border-blue-500' : 'dark:border-transparent text-gray-500 hover:text-gray-600 dark:text-gray-400 border-gray-100 hover:border-gray-300 dark:border-gray-700 dark:hover:text-gray-300'}}"
                        id="notifications-tab" data-tabs-target="#notifications" type="button" role="tab" aria-controls="notifications" aria-selected="{{ $activeTab == "notifications" ? 'true' : 'false' }}">
                    {{__("Notifications")}}</button>
            </li>
        </ul>
    </div>
    <div id="myTabContent">
        <div class="{{ $activeTab == "create" ? '' : 'hidden' }}" id="create" role="tabpanel" aria-labelledby="create-tab">
            @include('notifications.includes.create')
        </div>
        <div class="{{ $activeTab == "notifications" ? '' : 'hidden' }}" id="notifications" role="tabpanel" aria-labelledby="notifications-tab">
            @include('notifications.includes.index')
        </div>
    </div>
</x-sidebar>

