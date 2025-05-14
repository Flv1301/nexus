<?php
/**
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 10/08/2023
 * @copyright NIP CIBER-LAB @2023
 */
namespace App\Http\Controllers\Service;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * @return Factory|View|Application
     */
    public function index(): Factory|View|Application
    {
        $user = Auth::user();
        $notifications = $user->notifications;

        return view('notifications', compact('notifications'));
    }

    /**
     * @param $notificationId
     * @return RedirectResponse
     */
    public function markAsRead($notificationId): RedirectResponse
    {
        $notification = Auth::user()->notifications()->findOrFail($notificationId);
        $notification->markAsRead();
        toast('Notificação marcada como lida!', 'success');

        return back();
    }
}
