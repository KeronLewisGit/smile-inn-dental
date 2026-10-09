<x-site.layout title="Contact" description="Contact Smile Inn Dental: #24 Mucurapo Road, St. James. Call +1 868-241-3688 or send us a message.">
@php($clinic = config('clinic'))
<section class="wrap grid gap-12 py-16 lg:grid-cols-12 lg:py-24">
    <div class="lg:col-span-5">
        <p class="eyebrow">Contact</p>
        <h1 class="h-display mt-5">Let's get you <em class="italic font-normal text-gold-deep">smiling</em>.</h1>
        <p class="lede mt-5">Questions? Ready to book? Whatever you need, we're here to help.</p>
        <ul class="mt-10 space-y-6 text-sm">
            <li class="flex gap-4"><span class="inline-flex size-11 shrink-0 items-center justify-center rounded-2xl bg-gold-soft text-gold-deep"><x-icon name="map" class="size-5" /></span><div><p class="font-semibold">Visit</p><p class="mt-1 text-stone">{{ $clinic['address']['line1'] }}, {{ $clinic['address']['line2'] }}, {{ $clinic['address']['country'] }}<br><a href="{{ $clinic['map_url'] }}" target="_blank" rel="noopener" class="link-arrow mt-1 text-gold-deep">Directions <x-icon name="arrow" /></a></p></div></li>
            <li class="flex gap-4"><span class="inline-flex size-11 shrink-0 items-center justify-center rounded-2xl bg-gold-soft text-gold-deep"><x-icon name="phone" class="size-5" /></span><div><p class="font-semibold">Call or WhatsApp</p><p class="mt-1 text-stone"><a href="tel:{{ $clinic['phone_href'] }}" class="hover:text-ink">{{ $clinic['phone'] }}</a> · <a href="https://wa.me/{{ $clinic['whatsapp'] }}" target="_blank" rel="noopener" class="hover:text-ink">WhatsApp</a></p></div></li>
            <li class="flex gap-4"><span class="inline-flex size-11 shrink-0 items-center justify-center rounded-2xl bg-gold-soft text-gold-deep"><x-icon name="mail" class="size-5" /></span><div><p class="font-semibold">Email</p><p class="mt-1 text-stone"><a href="mailto:{{ $clinic['email'] }}" class="hover:text-ink">{{ $clinic['email'] }}</a></p></div></li>
            <li class="flex gap-4"><span class="inline-flex size-11 shrink-0 items-center justify-center rounded-2xl bg-gold-soft text-gold-deep"><x-icon name="clock" class="size-5" /></span><div><p class="font-semibold">Hours</p><div class="mt-1 grid grid-cols-[auto_1fr] gap-x-6 gap-y-1 text-stone">@foreach ($hours as $d => $h)<span>{{ $d }}</span><span>{{ $h }}</span>@endforeach</div></div></li>
        </ul>
        <div class="mt-8 flex gap-2">@foreach ([['instagram', $clinic['social']['instagram']], ['tiktok', $clinic['social']['tiktok']], ['x', $clinic['social']['x']]] as [$i, $u])<a href="{{ $u }}" target="_blank" rel="noopener" class="inline-flex size-10 items-center justify-center rounded-full ring-1 ring-line hover:bg-ink hover:text-ivory" aria-label="{{ $i }}"><x-icon :name="$i" class="size-4" /></a>@endforeach<span class="ml-2 self-center text-sm text-stone">follow {{ $clinic['handle'] }}</span></div>
    </div>
    <div class="lg:col-span-7">
        <form method="post" action="{{ route('contact.store') }}" class="card grid gap-5 sm:grid-cols-2 sm:p-10">
            @csrf<input type="text" name="website" tabindex="-1" autocomplete="off" class="hidden">
            <h2 class="font-display text-3xl sm:col-span-2">Send us a message</h2>
            <x-site.field label="Your name" name="name" required><input id="name" name="name" value="{{ old('name') }}" required class="input" autocomplete="name"></x-site.field>
            <x-site.field label="Email" name="email" required><input id="email" name="email" type="email" value="{{ old('email') }}" required class="input" autocomplete="email"></x-site.field>
            <x-site.field label="Phone" name="phone"><input id="phone" name="phone" type="tel" value="{{ old('phone') }}" class="input" autocomplete="tel" placeholder="(868)"></x-site.field>
            <x-site.field label="What service are you interested in?" name="service"><select id="service" name="service" class="input"><option value="">Select one…</option>@foreach ($clinicInterests = config('clinic.interests') as $s)<option @selected(old('service') === $s)>{{ $s }}</option>@endforeach</select></x-site.field>
            <x-site.field label="Gender (optional)" name="gender"><select id="gender" name="gender" class="input"><option value="">Prefer not to say</option><option @selected(old('gender') === 'Female')>Female</option><option @selected(old('gender') === 'Male')>Male</option></select></x-site.field>
            <x-site.field label="Let us know more" name="message" required class="sm:col-span-2"><textarea id="message" name="message" rows="5" required class="input" placeholder="Tell us what you are hoping for, or what is bothering you.">{{ old('message') }}</textarea></x-site.field>
            <div class="flex flex-wrap items-center gap-4 sm:col-span-2"><button class="btn-gold btn-lg">Send message</button><span class="text-sm text-stone">Or <a href="{{ route('book') }}" class="font-semibold text-gold-deep hover:underline">book online</a> straight away.</span></div>
        </form>
    </div>
</section>
</x-site.layout>
