<?php
namespace App\Http\ViewComposers;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class UserNotificationComposer
{
    public function compose(View $view)
    {
        if (Auth::check()) {
            $user = Auth::user();
            $notifications = $user->notifications()->take(10)->get();
            $unreadCount = $user->unreadNotifications()->count();

            $view->with('userNotifications', $notifications)
                ->with('unreadNotificationCount', $unreadCount);
        }
    }
}
