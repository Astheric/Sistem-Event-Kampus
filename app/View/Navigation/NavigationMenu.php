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
                ['label' => 'Moderasi event', 'icon' => 'check-circle', 'route' => 'admin.moderation.index', 'active' => 'admin.moderation.*'],
                ['label' => 'Kelola event', 'icon' => 'calendar', 'route' => 'organizer.events.index', 'active' => 'organizer.events.*'],
                ['label' => 'Kategori', 'icon' => 'tag', 'route' => 'admin.categories.index', 'active' => 'admin.categories.*'],
                ['label' => 'Pengguna & peran', 'icon' => 'users', 'route' => 'admin.users.index', 'active' => 'admin.users.*'],
                ['label' => 'Audit log', 'icon' => 'file-text', 'route' => 'admin.audit_logs.index', 'active' => 'admin.audit_logs.*'],
            ];
        } elseif ($user->isOrganizer()) {
            $items = [
                ['label' => 'Dashboard', 'icon' => 'home', 'route' => 'organizer.dashboard', 'active' => 'organizer.dashboard'],
                ['label' => 'Event saya', 'icon' => 'calendar', 'route' => 'organizer.events.index', 'active' => 'organizer.events.*'],
                ['label' => 'Profil organisasi', 'icon' => 'user', 'route' => 'participant.profile', 'active' => 'participant.profile'],
                ['label' => 'Katalog event', 'icon' => 'compass', 'route' => 'events.index', 'active' => 'events.*'],
            ];
        } else {
            $items = [
                ['label' => 'Tiket saya', 'icon' => 'tag', 'route' => 'participant.tickets.index', 'active' => 'participant.tickets.*'],
                ['label' => 'Sertifikat saya', 'icon' => 'book-open', 'route' => 'participant.certificates.index', 'active' => 'participant.certificates.*'],
                ['label' => 'Katalog event', 'icon' => 'compass', 'route' => 'events.index', 'active' => 'events.*'],
                ['label' => 'Profil akun', 'icon' => 'user', 'route' => 'participant.profile', 'active' => 'participant.profile'],
            ];
        }

        // Committee assignments (accessible for participants/organizers delegated as committee)
        if ($user->eventMembers()->exists()) {
            $items[] = [
                'label' => 'Tugas panitia',
                'icon' => 'clipboard',
                'route' => 'organizer.committee.tasks',
                'active' => 'organizer.committee.tasks',
            ];
        }

        return $items;
    }
}
