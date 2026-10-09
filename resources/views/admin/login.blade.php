<!doctype html><html lang="en" class="h-full"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><meta name="robots" content="noindex"><title>Staff login · Smile Inn</title><link rel="icon" href="{{ asset('favicon.png') }}">@vite(['resources/css/app.css', 'resources/js/app.js'])</head>
<body class="flex min-h-full items-center justify-center bg-ink p-6">
    <form method="post" action="{{ route('admin.login.attempt') }}" class="w-full max-w-sm rounded-3xl bg-ivory p-8 shadow-soft">@csrf
        <img src="{{ asset('images/logo-gold.png') }}" alt="Smile Inn" class="h-9"><h1 class="mt-6 font-display text-3xl">Staff sign in</h1>
        <div class="mt-6 space-y-4">
            <x-admin.field label="Email" name="email"><input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus class="input" autocomplete="username"></x-admin.field>
            <x-admin.field label="Password" name="password"><input id="password" name="password" type="password" required class="input" autocomplete="current-password"></x-admin.field>
            <label class="flex items-center gap-2 text-sm"><input type="checkbox" name="remember" class="check">Keep me signed in</label>
        </div>
        <button class="btn-gold mt-6 w-full">Sign in</button>
        <p class="mt-5 text-center text-xs text-stone"><a href="{{ route('home') }}" class="hover:underline">← Back to the website</a></p>
    </form>
</body></html>
