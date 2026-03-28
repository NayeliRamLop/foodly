<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NotificationController extends Controller
{
    public function index(Request $request): View
    {
        $notifications = $request->user()
            ->notifications()
            ->latest()
            ->paginate(15);

        return view('notifications.index', [
            'notifications' => $notifications,
        ]);
    }

    public function markAsRead(Request $request, string $notification): RedirectResponse
    {
        $item = $request->user()
            ->notifications()
            ->where('id', $notification)
            ->firstOrFail();

        if (!$item->read_at) {
            $item->markAsRead();
        }

        return back();
    }

    public function markAllAsRead(Request $request): RedirectResponse
    {
        $request->user()->unreadNotifications->markAsRead();

        return back();
    }

    public function open(Request $request, string $notification): RedirectResponse
    {
        $item = $request->user()
            ->notifications()
            ->where('id', $notification)
            ->firstOrFail();

        if (!$item->read_at) {
            $item->markAsRead();
        }

        $url = $this->resolveNotificationUrl($item);

        if (is_string($url) && $url !== '') {
            return redirect()->to($url);
        }

        return redirect()->route('user.perfil');
    }

    private function resolveNotificationUrl(object $notification): ?string
    {
        $url = data_get($notification->data, 'url');

        if (is_string($url) && $url !== '' && $url !== route('recipes.index')) {
            return $url;
        }

        $type = data_get($notification->data, 'type');
        $recipeId = data_get($notification->data, 'recipe_id');
        $userId = data_get($notification->data, 'user_id');

        if (in_array($type, ['recipe_like', 'recipe_comment'], true) && $recipeId) {
            return route('recipes.index', ['open_recipe' => $recipeId]);
        }

        if ($type === 'follow' && $userId) {
            return route('profile.public', $userId);
        }

        return is_string($url) && $url !== '' ? $url : null;
    }
}
