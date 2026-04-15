<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Display a listing of the notifications.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $notifications = auth()->user()
            ->notifications()
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('notifications.index', compact('notifications'));
    }

    /**
     * Mark the specified notification as read.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function markAsRead($id)
    {
        $notification = auth()->user()
            ->notifications()
            ->where('id', $id)
            ->firstOrFail();

        $notification->markAsRead();

        return response()->json(['success' => true]);
    }

    /**
     * Mark all notifications as read.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function markAllAsRead()
    {
        auth()->user()->unreadNotifications->markAsRead();
        return back()->with('success', 'All notifications marked as read');
    }

    /**
     * Remove the specified notification from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $notification = auth()->user()
            ->notifications()
            ->where('id', $id)
            ->firstOrFail();

        $notification->delete();

        return back()->with('success', 'Notification deleted');
    }

    /**
     * Mark notification as read and redirect to its link.
     */
    public function read($notificationId)
    {
        $user = auth()->user();
        if (! $user) {
            return redirect()->route('login');
        }

        $notification = $user->notifications()->where('id', $notificationId)->first();

        if (! $notification) {
            return back()->with('error', 'Notification not found.');
        }

        // mark as read
        $notification->markAsRead();

        // redirect to stored link or fallback to notifications index/home
        $link = data_get($notification->data, 'link', data_get($notification->data, 'action_url', route('jcr.index')));
        return redirect($link);
    }
}