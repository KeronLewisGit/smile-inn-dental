@props(['title' => null, 'description' => null, 'dark' => false])
@php($clinic = config('clinic'))
<!doctype html>
<html lang="en" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ? $title.' · ' : '' }}{{ $clinic['name'] }} · St. James, Trinidad</title>
    <meta name="description" content="{{ $description ?? 'Smile Inn Dental is the Caribbean\'s Emerald-certified Invisalign clinic in St. James, Port of Spain. Award-winning general, cosmetic and children\'s dentistry. Book online.' }}">
    <meta property="og:title" content="{{ $title ?? $clinic['name'] }}">
    <meta property="og:description" content="{{ $description ?? $clinic['tagline'] }}">
    <meta property="og:image" content="{{ asset('images/clinic/doctors-wide.jpg') }}">
    <link rel="icon" href="{{ asset('favicon.png') }}">
    <script type="application/ld+json">{!! json_encode(['@context' => 'https://schema.org', '@type' => 'Dentist', 'name' => $clinic['name'], 'telephone' => $clinic['phone'], 'email' => $clinic['email'], 'url' => url('/'), 'image' => asset('images/clinic/doctors-wide.jpg'), 'address' => ['@type' => 'PostalAddress', 'streetAddress' => $clinic['address']['line1'], 'addressLocality' => 'St. James, Port of Spain', 'addressCountry' => 'TT'], 'sameAs' => array_values($clinic['social'])], JSON_UNESCAPED_SLASHES) !!}</script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-full flex flex-col">
    <a href="#main" class="sr-only focus:not-sr-only focus:fixed focus:top-3 focus:left-3 focus:z-50 btn-gold">Skip to content</a>
    <x-site.nav />
    <main id="main" class="flex-1">
        @if (session('status'))<div class="wrap pt-6"><div role="status" class="rounded-2xl bg-sage px-5 py-4 text-sm font-semibold text-sage-deep ring-1 ring-sage-deep/20">{{ session('status') }}</div></div>@endif
        {{ $slot }}
    </main>
    <x-site.footer />
    <a href="https://wa.me/{{ $clinic['whatsapp'] }}?text={{ urlencode('Hi Smile Inn, I would like to book an appointment.') }}" target="_blank" rel="noopener" class="fixed bottom-5 right-5 z-40 inline-flex items-center gap-2 rounded-full bg-ink px-4 py-3 text-sm font-semibold text-ivory shadow-soft ring-1 ring-white/10 transition hover:bg-gold hover:text-ink sm:bottom-6 sm:right-6" aria-label="Chat on WhatsApp"><x-icon name="whatsapp" class="size-5" /><span class="hidden sm:inline">WhatsApp us</span></a>
</body>
</html>
