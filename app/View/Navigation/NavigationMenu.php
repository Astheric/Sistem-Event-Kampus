<?php

namespace App\View\Navigation;

use App\Models\User;

class NavigationMenu
{
    /**
     * @return array<int, array{label: string, icon: string, route: string, active: string}>
     */
    public function for(?User $user): array
    {
        if (! $user) {
            return [];
        }

        $items = [];

        if ($user->isAdmin()) {
            $items = [
                ['label' => 'Dashboard', 'icon' => 'home', 'route' => 'admin.dashboard', 'active' => 'admin.dashboard'],
                ['label' => 'Moderasi event', 'icon' => 'check-square', 'route' => 'admin.moderation.index', 'active' => 'admin.moderation.*'],
                ['label' => 'Kategori', 'icon' => 'grid', 'route' => 'admin.categories.index', 'active' => 'admin.categories.*'],
                ['label' => 'Pengguna', 'icon' => 'users', 'route' => 'admin.users.index', 'active' => 'admin.users.*'],
                ['label' => 'Audit log', 'icon' => 'file-text', 'route' => 'admin.audit_logs.index', 'active' => 'admin.audit_logs.*'],
            ];
        } elseif ($user->isOrganizer()) {
            $items = [
                ['label' => 'Dashboard', 'icon' => 'home', 'route' => 'organizer.dashboard', 'active' => 'organizer.dashboard'],
                ['label' => 'Event saya', 'icon' => 'calendar', 'route' => 'organizer.events.index', 'active' => 'organizer.events.*'],
            ];
        } else {
            $items = [
                ['label' => 'Dashboard', 'icon' => 'home', 'route' => 'home', 'active' => 'home'],
                ['label' => 'Jelajahi event', 'icon' => 'compass', 'route' => 'events.index', 'active' => 'events.*'],
                ['label' => 'Tiket saya', 'icon' => 'tag', 'route' => 'participant.tickets.index', 'active' => 'participant.tickets.*'],
                ['label' => 'Sertifikat saya', 'icon' => 'award', 'route' => 'participant.certificates.index', 'active' => 'participant.certificates.*'],
                ['label' => 'Profil', 'icon' => 'user', 'route' => 'participant.profile', 'active' => 'participant.profile'],
            ];
        }

        // Committee tasks
        if ($user->eventMembers()->exists()) {
            $items[] = ['label' => 'Tugas panitia', 'icon' => 'clipboard', 'route' => 'organizer.events.index', 'active' => 'organizer.participants.*'];
        }

        return $items;
    }
}
