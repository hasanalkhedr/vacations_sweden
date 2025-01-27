<?php

namespace App\Http\Controllers\Notifications;

use App\Models\Employee;
use App\Models\Notification;
use App\Models\NotificationsGroup;
use Illuminate\Http\Request;
use Ramsey\Uuid\Type\Hexadecimal;

class NotificationController
{
//    public function create()
//    {
//        return view('notifications.includes.create');
//    }

    public function store(Request $request) {

        $users = Employee::all()->except(auth()->user()->id);

        $notification_group = NotificationsGroup::create([
           'title' => $request->title,
           'body' => $request->body
        ]);

        $notification = new \MBarlow\Megaphone\Types\General(
            $request->title, // Notification Title
            $request->body, // Notification Body
        );

        foreach ($users as $user) {
            $user->notify($notification);
            $notification_instance = $user->notifications->first();
            $notification_instance->notifications_group_id = $notification_group->id;
            $notification_instance->save();
        }

        session()->flash('megaphone_success', __('Notifications sent successfully!'));

        return redirect()->route('notifications.tabbed_view');
    }

    public function tabbed_view(Request $request) {

        $notificationsPage = $request->query('notifications_page', 1);

        $activeTab = $request->query('active_tab', 'create');

        $notifications = NotificationsGroup::paginate(10, ['*'], 'notifications_page');

        $notifications->appends(['active_tab' => $activeTab]);

        return view('notifications.notifications', [
            'notifications' => $notifications,
            'activeTab' => $activeTab
        ]);
    }

    public function show(Request $request, NotificationsGroup $notificationsGroup) {
        return view('notifications.show', [
            'current_page' => $request->current_page,
            'notification' => $notificationsGroup,
        ]);
    }

    public function destroy(NotificationsGroup $notificationsGroup) {
        Notification::where('notifications_group_id', $notificationsGroup->id)->delete();
        $notificationsGroup->delete();
        return back();
    }
}
