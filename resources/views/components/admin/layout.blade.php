@props(['title' => 'Admin'])
@php($nav = [
    ['admin.dashboard', 'Dashboard', 'home', 'admin.dashboard'],
    ['admin.calendar', 'Calendar', 'calendar', 'admin.calendar'],
    ['admin.appointments.index', 'Appointments', 'list', 'admin.appointments.*'],
    ['admin.patients.index', 'Patients', 'users', 'admin.patients.*'],
    ['admin.inquiries.index', 'Enquiries', 'inbox', 'admin.inquiries.*'],
    ['admin.subscribers.index', 'Subscribers', 'mail', 'admin.subscribers.*'],
])
@php($content = [
    ['admin.services.index', 'Services', 'tooth', 'admin.services.*'],
    ['admin.team.index', 'Team', 'heart', 'admin.team.*'],
    ['admin.testimonials.index', 'Testimonials', 'star', 'admin.testimonials.*'],
    ['admin.posts.index', 'Journal posts', 'pen', 'admin.posts.*'],
    ['admin.appointment-types.index', 'Booking types', 'clock', 'admin.appointment-types.*'],
])
<!doctype html>
<html lang="en" class="h-full">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><meta name="robots" content="noindex">
    <title>{{ $title }} · Smile Inn Admin</title>
    <link rel="icon" href="{{ asset('favicon.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-full bg-sand/40" x-data="{ side: false }">
    <div class="flex min-h-screen">
        <aside class="fixed inset-y-0 left-0 z-40 w-64 -translate-x-full border-r border-line bg-ivory p-4 transition lg:static lg:translate-x-0" :class="side && 'translate-x-0'">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-2 py-2"><img src="{{ asset('images/logo-gold.png') }}" alt="Smile Inn" class="h-7"></a>
            <p class="mt-6 px-3 text-[10px] font-semibold uppercase tracking-widest text-stone">Clinic</p>
            <nav class="mt-2 space-y-0.5">@foreach ($nav as [$r, $l, $i, $m])<a href="{{ route($r) }}" class="adm-side-link {{ request()->routeIs($m) ? 'active' : '' }}"><x-icon :name="$i" />{{ $l }}</a>@endforeach</nav>
            <p class="mt-6 px-3 text-[10px] font-semibold uppercase tracking-widest text-stone">Website content</p>
            <nav class="mt-2 space-y-0.5">@foreach ($content as [$r, $l, $i, $m])<a href="{{ route($r) }}" class="adm-side-link {{ request()->routeIs($m) ? 'active' : '' }}"><x-icon :name="$i" />{{ $l }}</a>@endforeach</nav>
            @if (auth()->user()->isAdmin())<p class="mt-6 px-3 text-[10px] font-semibold uppercase tracking-widest text-stone">Admin</p><nav class="mt-2 space-y-0.5"><a href="{{ route('admin.settings') }}" class="adm-side-link {{ request()->routeIs('admin.settings*') ? 'active' : '' }}"><x-icon name="settings" />Hours &amp; booking</a><a href="{{ route('admin.users.index') }}" class="adm-side-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}"><x-icon name="shield" />Staff logins</a></nav>@endif
            <div class="mt-8 border-t border-line pt-4"><a href="{{ route('home') }}" target="_blank" class="adm-side-link"><x-icon name="external" />View website</a><form method="post" action="{{ route('admin.logout') }}">@csrf<button class="adm-side-link w-full"><x-icon name="logout" />Sign out</button></form><p class="mt-3 px-3 text-xs text-stone">{{ auth()->user()->name }} · {{ ucfirst(auth()->user()->role) }}</p></div>
        </aside>
        <div x-cloak x-show="side" @click="side = false" class="fixed inset-0 z-30 bg-ink/30 lg:hidden"></div>
        <div class="flex min-w-0 flex-1 flex-col">
            <header class="flex h-14 items-center justify-between border-b border-line bg-ivory px-4 lg:px-8"><div class="flex items-center gap-3"><button @click="side = true" class="rounded-lg p-2 ring-1 ring-line lg:hidden" aria-label="Menu"><x-icon name="menu" class="size-4" /></button><h1 class="font-display text-2xl">{{ $title }}</h1></div><div class="flex items-center gap-2">{{ $actions ?? '' }}</div></header>
            <main class="flex-1 p-4 lg:p-8">
                @if (session('saved'))<div role="status" class="mb-5 rounded-xl bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-900 ring-1 ring-emerald-200">{{ session('saved') }}</div>@endif
                @if (session('credentials'))<div class="mb-5 rounded-xl bg-gold-soft px-4 py-3 text-sm ring-1 ring-gold/40"><p class="font-semibold">Login details (shown once):</p><p class="mt-1 font-mono">{{ session('credentials')['email'] }} · {{ session('credentials')['password'] }}</p></div>@endif
                @if ($errors->any())<div role="alert" class="mb-5 rounded-xl bg-red-50 px-4 py-3 text-sm text-red-900 ring-1 ring-red-200"><p class="font-semibold">Please check the form.</p><ul class="mt-1 list-inside list-disc">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif
                {{ $slot }}
            </main>
        </div>
    </div>
</body>
</html>
