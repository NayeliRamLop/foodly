<?php

namespace App\Providers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use JeroenNoten\LaravelAdminLte\Events\BuildingMenu;

class AdminLteServiceProvider extends ServiceProvider
{
    public function boot()
    {
        \Event::listen(BuildingMenu::class, function (BuildingMenu $event) {
            $user = Auth::user();

            if (!$user || $user->isAdmin()) {
                return;
            }

            if (!Schema::hasTable('notifications')) {
                return;
            }

            $unreadCount = $user->unreadNotifications()->count();
            $recentNotifications = $user->notifications()->latest()->limit(5)->get();
            $submenu = [];

            if ($recentNotifications->isEmpty()) {
                $submenu[] = [
                    'text' => 'No tienes notificaciones',
                    'icon' => 'fas fa-inbox',
                    'url' => '#',
                ];
            } else {
                foreach ($recentNotifications as $notification) {
                    $message = (string) data_get($notification->data, 'message', 'Nueva notificacion');

                    if (mb_strlen($message) > 45) {
                        $message = mb_substr($message, 0, 42) . '...';
                    }

                    $type = data_get($notification->data, 'type', '');
                    $icon = match($type) {
                        'recipe_like'    => 'fas fa-heart',
                        'recipe_comment' => 'fas fa-comment',
                        'follow'         => 'fas fa-user-plus',
                        'profile_visit'  => 'fas fa-eye',
                        default          => 'fas fa-bell',
                    };
                    $iconColor = match($type) {
                        'recipe_like'    => 'danger',
                        'recipe_comment' => 'warning',
                        'follow'         => 'success',
                        'profile_visit'  => 'info',
                        default          => $notification->read_at ? 'secondary' : 'primary',
                    };

                    $submenu[] = [
                        'text'       => $message,
                        'icon'       => $icon,
                        'icon_color' => $notification->read_at ? 'secondary' : $iconColor,
                        'url'        => route('notifications.open', $notification->id),
                    ];
                }
            }

            if (!$recentNotifications->isEmpty()) {
                $submenu[] = [
                    'text'       => 'Ver todas las notificaciones',
                    'icon'       => 'fas fa-list',
                    'icon_color' => 'secondary',
                    'url'        => route('notifications.index'),
                ];
            }

            $event->menu->addBefore('topnav_profile', [
                'key' => 'topnav_notifications',
                'text' => '',
                'icon' => 'fas fa-bell',
                'icon_color' => $unreadCount > 0 ? 'danger' : null,
                'label' => $unreadCount > 0 ? (string) $unreadCount : null,
                'label_color' => 'danger',
                'topnav_right' => true,
                'submenu' => $submenu,
            ]);
        });
    }
}
