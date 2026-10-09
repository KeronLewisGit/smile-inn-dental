<x-site.layout title="Book Online" description="Book an appointment at Smile Inn Dental online: general consultations, cleanings, free Invisalign consultations and free virtual cosmetic consults.">
@php($clinic = config('clinic'))
@php($typeData = $types->map(fn ($t) => ['slug' => $t->slug, 'name' => $t->name, 'description' => $t->description, 'duration' => $t->duration_minutes, 'free' => $t->is_free, 'virtual' => $t->is_virtual, 'provider' => $t->provider?->name])->values())
<section class="wrap py-14 lg:py-20" x-data="booking({ types: @js($typeData), slotsUrl: @js(route('book.slots')), initialType: @js(old('type', $selected?->slug)), initialDate: @js(old('date')), initialTime: @js(old('time')) })">
    <div class="grid gap-12 lg:grid-cols-12">
        <div class="lg:col-span-8">
            <p class="eyebrow">Book with us today</p>
            <h1 class="h-display mt-4">Choose a time that <em class="italic font-normal text-gold-deep">suits you</em>.</h1>
            <p class="lede mt-4">Three quick steps. We confirm by email, usually within the hour during opening times.</p>
            @if ($errors->any())<div class="mt-6 rounded-2xl bg-red-50 px-5 py-4 text-sm text-red-900 ring-1 ring-red-200"><p class="font-semibold">Please check the form.</p><ul class="mt-1 list-inside list-disc">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif

            <form method="post" action="{{ route('book.store') }}" class="mt-10 space-y-8">
                @csrf<input type="text" name="website" tabindex="-1" autocomplete="off" class="hidden">
                <input type="hidden" name="type" :value="type"><input type="hidden" name="date" :value="date"><input type="hidden" name="time" :value="time">

                {{-- Step 1 --}}
                <section class="card">
                    <header class="flex items-center justify-between"><h2 class="flex items-center gap-3 font-display text-2xl"><span class="flex size-8 items-center justify-center rounded-full bg-ink text-sm text-gold">1</span>What would you like to book?</h2><button type="button" x-show="type && step !== 1" @click="step = 1" class="text-xs font-semibold text-gold-deep hover:underline">Change</button></header>
                    <div class="mt-5 grid gap-3 sm:grid-cols-2" x-show="step === 1 || !type">
                        <template x-for="t in types" :key="t.slug">
                            <button type="button" @click="choose(t.slug)" class="flex items-start gap-3 rounded-2xl p-4 text-left ring-1 transition hover:ring-gold" :class="type === t.slug ? 'bg-gold-soft ring-gold' : 'bg-ivory ring-line'">
                                <span class="mt-0.5 inline-flex size-5 shrink-0 items-center justify-center rounded-full ring-1 ring-ink/20" :class="type === t.slug && 'bg-ink text-gold ring-ink'"><svg x-show="type === t.slug" class="size-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="m5 12 4.5 4.5L19 7"/></svg></span>
                                <span><span class="flex flex-wrap items-center gap-2 font-semibold" x-text="t.name"></span><span class="mt-1 block text-xs text-stone" x-text="t.description"></span><span class="mt-2 flex flex-wrap gap-1.5"><span class="chip bg-white text-stone ring-1 ring-line" x-text="t.duration + ' min'"></span><span x-show="t.free" class="chip bg-sage text-sage-deep">Free</span><span x-show="t.virtual" class="chip bg-sky-50 text-sky-800">Virtual</span><span x-show="t.provider" class="chip bg-white text-stone ring-1 ring-line" x-text="t.provider"></span></span></span>
                            </button>
                        </template>
                    </div>
                    <p x-show="type && step !== 1" x-cloak class="mt-3 text-sm text-stone">Selected: <strong class="text-ink" x-text="selected?.name"></strong></p>
                </section>

                {{-- Step 2 --}}
                <section class="card" x-show="type" x-cloak>
                    <header class="flex items-center justify-between"><h2 class="flex items-center gap-3 font-display text-2xl"><span class="flex size-8 items-center justify-center rounded-full bg-ink text-sm text-gold">2</span>Pick a date and time</h2><button type="button" x-show="time && step !== 2" @click="step = 2" class="text-xs font-semibold text-gold-deep hover:underline">Change</button></header>
                    <div class="mt-5 grid gap-6 md:grid-cols-2" x-show="step === 2 || !time">
                        <div>
                            <div class="flex items-center justify-between"><button type="button" @click="prevMonth()" :disabled="!canPrev" class="inline-flex size-9 items-center justify-center rounded-full ring-1 ring-line disabled:opacity-30" aria-label="Previous month"><x-icon name="chevron" class="size-4 rotate-180" /></button><p class="font-semibold" x-text="monthLabel"></p><button type="button" @click="nextMonth()" :disabled="!canNext" class="inline-flex size-9 items-center justify-center rounded-full ring-1 ring-line disabled:opacity-30" aria-label="Next month"><x-icon name="chevron" class="size-4" /></button></div>
                            <div class="mt-4 grid grid-cols-7 gap-1 text-center text-[11px] font-semibold uppercase tracking-wider text-stone">@foreach (['Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa', 'Su'] as $d)<span>{{ $d }}</span>@endforeach</div>
                            <div class="mt-1 grid grid-cols-7 gap-1">
                                <template x-for="(c, i) in grid" :key="i"><div><button type="button" x-show="c" @click="pick(c.iso)" :disabled="!c?.open" class="flex aspect-square w-full items-center justify-center rounded-xl text-sm font-semibold transition disabled:text-stone/40" :class="date === c?.iso ? 'bg-ink text-gold' : (c?.open ? 'bg-gold-soft text-ink hover:bg-gold' : '')" x-text="c?.d"></button></div></template>
                            </div>
                            <p class="mt-3 text-xs text-stone" x-show="loading">Checking availability…</p>
                            <p class="mt-3 text-xs text-stone" x-show="!loading && !openDays.length">No online slots in the next {{ $horizon }} days for this type. Please call us on {{ $clinic['phone'] }}.</p>
                        </div>
                        <div>
                            <p class="label" x-text="date ? new Date(date + 'T00:00:00').toLocaleDateString('en-GB', { weekday: 'long', day: 'numeric', month: 'long' }) : 'Choose a day first'"></p>
                            <div class="mt-2 grid grid-cols-3 gap-2" x-show="slots.length">
                                <template x-for="s in slots" :key="s.time"><button type="button" :disabled="!s.available" @click="pickTime(s.time)" class="rounded-xl px-2 py-2.5 text-sm font-semibold ring-1 transition disabled:line-through disabled:opacity-40" :class="time === s.time ? 'bg-ink text-gold ring-ink' : 'bg-ivory ring-line hover:ring-gold'" x-text="s.label"></button></template>
                            </div>
                            <p class="mt-2 text-xs text-stone" x-show="date && !loading && !slots.some(s => s.available)">Fully booked that day. Try another date.</p>
                        </div>
                    </div>
                    <p x-show="time && step !== 2" x-cloak class="mt-3 text-sm text-stone">Selected: <strong class="text-ink" x-text="summary"></strong></p>
                </section>

                {{-- Step 3 --}}
                <section id="details" class="card" x-show="time" x-cloak>
                    <h2 class="flex items-center gap-3 font-display text-2xl"><span class="flex size-8 items-center justify-center rounded-full bg-ink text-sm text-gold">3</span>Your details</h2>
                    <div class="mt-5 grid gap-5 sm:grid-cols-2">
                        <x-site.field label="First name" name="first_name" required><input id="first_name" name="first_name" value="{{ old('first_name') }}" required class="input" autocomplete="given-name"></x-site.field>
                        <x-site.field label="Last name" name="last_name" required><input id="last_name" name="last_name" value="{{ old('last_name') }}" required class="input" autocomplete="family-name"></x-site.field>
                        <x-site.field label="Email" name="email" required hint="Your confirmation goes here."><input id="email" name="email" type="email" value="{{ old('email') }}" required class="input" autocomplete="email"></x-site.field>
                        <x-site.field label="Phone" name="phone" required hint="In case we need to reach you quickly."><input id="phone" name="phone" type="tel" value="{{ old('phone') }}" required class="input" autocomplete="tel" placeholder="(868) 000-0000"></x-site.field>
                        <x-site.field label="Anything we should know?" name="notes" class="sm:col-span-2"><textarea id="notes" name="notes" rows="3" class="input" placeholder="What is bothering you, or what you are hoping for.">{{ old('notes') }}</textarea></x-site.field>
                        <label class="flex items-center gap-3 text-sm sm:col-span-2"><input type="checkbox" name="new_patient" value="1" class="check" @checked(old('new_patient'))>This is my first visit to Smile Inn</label>
                        <label class="flex items-center gap-3 text-sm sm:col-span-2"><input type="checkbox" name="marketing_opt_in" value="1" class="check" @checked(old('marketing_opt_in'))>Send me occasional offers and oral health tips</label>
                    </div>
                    <div class="mt-7 flex flex-wrap items-center gap-4"><button class="btn-gold btn-lg">Request appointment</button><p class="text-xs text-stone">By booking you agree to our <a href="{{ route('privacy') }}" class="underline">privacy policy</a>. No payment is taken online.</p></div>
                </section>
            </form>
        </div>
        <aside class="space-y-5 lg:col-span-4">
            <div class="card-sand"><p class="eyebrow">Your booking</p><p class="mt-3 font-display text-2xl" x-text="summary || 'Nothing selected yet'"></p><p class="mt-2 text-sm text-stone">Pending until we confirm. You can cancel from the link in your email.</p></div>
            <div class="card"><p class="eyebrow">Opening hours</p><dl class="mt-4 divide-y divide-line text-sm">@foreach ($hours as $d => $h)<div class="flex justify-between py-2"><dt class="font-semibold">{{ $d }}</dt><dd class="{{ $h === 'Closed' ? 'text-stone' : '' }}">{{ $h }}</dd></div>@endforeach</dl></div>
            <div class="card"><p class="eyebrow">Prefer to talk?</p><p class="mt-3 text-sm text-stone">Call <a href="tel:{{ $clinic['phone_href'] }}" class="font-semibold text-ink">{{ $clinic['phone'] }}</a> or <a href="https://wa.me/{{ $clinic['whatsapp'] }}" target="_blank" rel="noopener" class="font-semibold text-ink">WhatsApp us</a>. For a dental emergency, call first.</p><a href="{{ $clinic['external_booking_url'] }}" target="_blank" rel="noopener" class="link-arrow mt-4 text-xs">Existing patient portal <x-icon name="external" /></a></div>
        </aside>
    </div>
</section>
</x-site.layout>
