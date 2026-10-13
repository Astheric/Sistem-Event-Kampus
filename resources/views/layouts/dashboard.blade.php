@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        <!-- Sidebar Navigation -->
        <aside class="lg:col-span-1 space-y-6">
            <!-- User Status Card -->
            <div class="bg-white border-3 border-black p-5 shadow-[5px_5px_0px_0px_#000]">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-[#FFE600] border-2 border-black flex items-center justify-center font-black text-xl shadow-[2px_2px_0px_0px_#000]">
                        {{ substr(Auth::user()->name, 0, 2) }}
                    </div>
                    <div class="overflow-hidden">
                        <h2 class="font-black text-base truncate">{{ Auth::user()->name }}</h2>
                        <span class="inline-block bg-black text-white text-[11px] font-black px-2 py-0.5 uppercase tracking-wider">
                            {{ Auth::user()->role }}
                        </span>
                    </div>
                </div>

                @if(Auth::user()->organization)
                    <div class="mt-4 pt-3 border-t-2 border-black text-xs font-bold text-zinc-700">
                        <p class="uppercase text-[10px] text-zinc-500">Organisasi:</p>
                        <p class="font-black text-black">{{ Auth::user()->organization->name }}</p>
                    </div>
                @endif
            </div>

            <!-- Navigation Links Card -->
            <nav class="bg-white border-3 border-black p-4 shadow-[5px_5px_0px_0px_#000] space-y-2">
                @if(request()->is('admin*'))
                    <div class="text-[11px] font-black uppercase tracking-wider text-zinc-500 px-3 py-1 bg-zinc-100 border border-black mb-2">
                        Panel Administrator
                    </div>
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2 px-3 py-2.5 font-bold text-sm border-2 transition-all {{ request()->routeIs('admin.dashboard') ? 'bg-[#FFE600] border-black shadow-[3px_3px_0px_0px_#000]' : 'border-transparent hover:bg-zinc-100 hover:border-black' }}">
                        <span>📊</span> Overview Platform
                    </a>
                    <a href="{{ route('admin.moderation.index') }}" class="flex items-center justify-between px-3 py-2.5 font-bold text-sm border-2 transition-all {{ request()->routeIs('admin.moderation.*') ? 'bg-[#FF6B4A] text-white border-black shadow-[3px_3px_0px_0px_#000]' : 'border-transparent hover:bg-zinc-100 hover:border-black' }}">
                        <span class="flex items-center gap-2"><span>🛡️</span> Moderasi Event</span>
                        @php $pendingCount = \App\Models\Event::where('status', 'SUBMITTED')->count(); @endphp
                        @if($pendingCount > 0)
                            <span class="bg-black text-white text-xs px-2 py-0.5 font-mono">{{ $pendingCount }}</span>
                        @endif
                    </a>
                    <a href="{{ route('admin.categories.index') }}" class="flex items-center gap-2 px-3 py-2.5 font-bold text-sm border-2 transition-all {{ request()->routeIs('admin.categories.*') ? 'bg-[#4DEEEA] border-black shadow-[3px_3px_0px_0px_#000]' : 'border-transparent hover:bg-zinc-100 hover:border-black' }}">
                        <span>🏷️</span> Master Kategori
                    </a>
                    <a href="{{ route('admin.users.index') }}" class="flex items-center gap-2 px-3 py-2.5 font-bold text-sm border-2 transition-all {{ request()->routeIs('admin.users.*') ? 'bg-[#FF70A6] border-black shadow-[3px_3px_0px_0px_#000]' : 'border-transparent hover:bg-zinc-100 hover:border-black' }}">
                        <span>👥</span> Manajemen User
                    </a>
                    <a href="{{ route('admin.audit_logs.index') }}" class="flex items-center gap-2 px-3 py-2.5 font-bold text-sm border-2 transition-all {{ request()->routeIs('admin.audit_logs.*') ? 'bg-[#57EB74] border-black shadow-[3px_3px_0px_0px_#000]' : 'border-transparent hover:bg-zinc-100 hover:border-black' }}">
                        <span>📜</span> Audit Trail Log
                    </a>
                @elseif(request()->is('organizer*'))
                    <div class="text-[11px] font-black uppercase tracking-wider text-zinc-500 px-3 py-1 bg-zinc-100 border border-black mb-2">
                        Panel Organizer
                    </div>
                    <a href="{{ route('organizer.dashboard') }}" class="flex items-center gap-2 px-3 py-2.5 font-bold text-sm border-2 transition-all {{ request()->routeIs('organizer.dashboard') ? 'bg-[#4DEEEA] border-black shadow-[3px_3px_0px_0px_#000]' : 'border-transparent hover:bg-zinc-100 hover:border-black' }}">
                        <span>📈</span> Dashboard Organizer
                    </a>
                    <a href="{{ route('organizer.events.index') }}" class="flex items-center gap-2 px-3 py-2.5 font-bold text-sm border-2 transition-all {{ request()->routeIs('organizer.events.index') || request()->routeIs('organizer.events.edit') || request()->routeIs('organizer.events.tickets') ? 'bg-[#FFE600] border-black shadow-[3px_3px_0px_0px_#000]' : 'border-transparent hover:bg-zinc-100 hover:border-black' }}">
                        <span>📅</span> Event Saya
                    </a>
                    <a href="{{ route('organizer.events.create') }}" class="flex items-center gap-2 px-3 py-2.5 font-bold text-sm border-2 transition-all {{ request()->routeIs('organizer.events.create') ? 'bg-[#57EB74] border-black shadow-[3px_3px_0px_0px_#000]' : 'border-transparent hover:bg-zinc-100 hover:border-black' }}">
                        <span>➕</span> Buat Event Baru
                    </a>
                @else
                    <div class="text-[11px] font-black uppercase tracking-wider text-zinc-500 px-3 py-1 bg-zinc-100 border border-black mb-2">
                        Dashboard Peserta
                    </div>
                    <a href="{{ route('participant.tickets.index') }}" class="flex items-center gap-2 px-3 py-2.5 font-bold text-sm border-2 transition-all {{ request()->routeIs('participant.tickets.*') ? 'bg-[#57EB74] border-black shadow-[3px_3px_0px_0px_#000]' : 'border-transparent hover:bg-zinc-100 hover:border-black' }}">
                        <span>🎟️</span> Tiket Saya
                    </a>
                    <a href="{{ route('participant.certificates.index') }}" class="flex items-center gap-2 px-3 py-2.5 font-bold text-sm border-2 transition-all {{ request()->routeIs('participant.certificates.*') ? 'bg-[#FF70A6] border-black shadow-[3px_3px_0px_0px_#000]' : 'border-transparent hover:bg-zinc-100 hover:border-black' }}">
                        <span>🎓</span> E-Sertifikat Saya
                    </a>
                    <a href="{{ route('participant.profile') }}" class="flex items-center gap-2 px-3 py-2.5 font-bold text-sm border-2 transition-all {{ request()->routeIs('participant.profile') ? 'bg-[#FFE600] border-black shadow-[3px_3px_0px_0px_#000]' : 'border-transparent hover:bg-zinc-100 hover:border-black' }}">
                        <span>👤</span> Edit Profil
                    </a>
                @endif

                <div class="pt-4 border-t-2 border-black space-y-1">
                    @if(Auth::user()->isAdmin() && !request()->is('admin*'))
                        <a href="{{ route('admin.dashboard') }}" class="block px-3 py-2 text-xs font-black bg-black text-white hover:bg-zinc-800 text-center">
                            ⚙️ Masuk Menu Admin
                        </a>
                    @endif
                    @if((Auth::user()->isOrganizer() || Auth::user()->isAdmin()) && !request()->is('organizer*'))
                        <a href="{{ route('organizer.dashboard') }}" class="block px-3 py-2 text-xs font-black bg-[#4DEEEA] border border-black hover:bg-cyan-300 text-center">
                            🏢 Masuk Menu Organizer
                        </a>
                    @endif
                    <a href="{{ route('events.index') }}" class="block px-3 py-2 text-xs font-bold hover:underline text-center">
                        ← Jelajah Event Publik
                    </a>
                </div>
            </nav>
        </aside>

        <!-- Main Dashboard Content -->
        <section class="lg:col-span-3">
            @yield('dashboard_content')
        </section>
    </div>
</div>
@endsection
