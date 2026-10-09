<x-site.layout title="The Clinic" description="The most high-end dental clinic in Trinidad: minimalist design, maximum comfort, and technology that sets new standards in dental care.">
@php($clinic = config('clinic'))
<section class="wrap py-16 lg:py-24">
    <div class="grid gap-12 lg:grid-cols-12 lg:items-end">
        <div class="lg:col-span-7"><p class="eyebrow">The clinic</p><h1 class="h-display mt-5">The most <em class="italic font-normal text-gold-deep">high-end</em> dental clinic.</h1><p class="lede mt-6 max-w-2xl">A full dental experience, delivered by a carefully selected team in a relaxed, friendly setting. We combine cutting-edge technology with dental artistry, so your care is as precise as it is comfortable.</p></div>
        <div class="grid grid-cols-3 gap-4 lg:col-span-5">@foreach ([[$clinic['stats']['years'], 'Years of experience'], [$clinic['stats']['specialists'], 'Specialists'], [$clinic['stats']['smiles'], 'Beautiful clients']] as [$n, $l])<div class="card-sand text-center"><p class="font-display text-4xl font-medium">{{ $n }}</p><p class="mt-1 text-xs font-semibold uppercase tracking-wider text-stone">{{ $l }}</p></div>@endforeach</div>
    </div>
    <div class="mt-14 frame aspect-[21/9] shadow-soft"><img src="{{ asset('images/clinic/lounge.webp') }}" alt="The Smile Inn lounge: white walls, natural light and greenery" class="h-full w-full object-cover"></div>
</section>

<section class="section bg-white">
    <div class="wrap grid gap-14 lg:grid-cols-2 lg:items-center">
        <x-site.heading eyebrow="Innovation" title="Technology that sets new standards in dental care." lede="We invested in the tools that make dentistry faster, gentler and more predictable, then designed a space calm enough to enjoy them in.">
            <ul class="mt-8 grid gap-5 sm:grid-cols-2">
                @foreach ([['scan', 'iTero 5D scanner', 'The first in the region with integrated imaging. No gooey impressions, and a 3D preview in seconds.'], ['sparkle', 'Digital Smile Design', 'Photos, video and 3D scans combined to design a smile that fits your face.'], ['shield', 'Modern imaging', 'Digital X-rays and diagnostics with lower radiation and clearer pictures.'], ['heart', 'Comfort first', 'Oral sedation, desensitising treatments and a team trained in dental anxiety.']] as [$i, $h, $t])
                    <li class="flex gap-3"><span class="inline-flex size-10 shrink-0 items-center justify-center rounded-xl bg-gold-soft text-gold-deep"><x-icon :name="$i" class="size-5" /></span><span><span class="block font-semibold">{{ $h }}</span><span class="mt-1 block text-sm text-stone">{{ $t }}</span></span></li>
                @endforeach
            </ul>
        </x-site.heading>
        <div class="grid grid-cols-2 gap-4"><div class="frame aspect-[3/4]"><img src="{{ asset('images/clinic/itero-scan.png') }}" alt="iTero scan" class="h-full w-full object-cover" loading="lazy"></div><div class="frame aspect-[3/4] mt-10"><img src="{{ asset('images/clinic/moment-6.webp') }}" alt="A relaxed patient" class="h-full w-full object-cover" loading="lazy"></div></div>
    </div>
</section>

<section class="section">
    <div class="wrap">
        <x-site.heading center eyebrow="Minimalist design, maximum comfort" title="Every area planned around simplicity, elegance and care." />
        <div class="mt-12 grid grid-cols-2 gap-4 md:grid-cols-3">@foreach ([1, 2, 3, 4, 5, 6] as $i)<div class="frame aspect-square reveal"><img src="{{ asset("images/clinic/moment-$i.webp") }}" alt="Smile Inn moment" class="h-full w-full object-cover" loading="lazy"></div>@endforeach</div>
    </div>
</section>

<section class="section bg-sand/60">
    <div class="wrap">
        <div class="flex flex-col gap-6 md:flex-row md:items-end md:justify-between"><x-site.heading eyebrow="The people" title="A carefully selected team." /><a href="{{ route('team') }}" class="link-arrow">Meet everyone <x-icon name="arrow" /></a></div>
        <div class="mt-12 grid grid-cols-2 gap-5 md:grid-cols-4 lg:grid-cols-7">@foreach ($team as $m)<a href="{{ route('team.show', $m) }}" class="group text-center"><div class="mx-auto aspect-square w-full overflow-hidden rounded-full bg-sand ring-2 ring-white"><img src="{{ $m->photoUrl() }}" alt="{{ $m->name }}" class="h-full w-full object-cover transition group-hover:scale-105" loading="lazy"></div><p class="mt-3 text-sm font-semibold">{{ $m->name }}</p><p class="text-xs text-stone">{{ $m->title }}</p></a>@endforeach</div>
    </div>
</section>

<section class="section">
    <div class="wrap grid gap-5 md:grid-cols-3">@foreach ($testimonials as $t)<x-site.testimonial :t="$t" compact />@endforeach</div>
</section>
<x-site.cta title="Start your Smile Inn journey." image="images/clinic/doctors-wide.jpg" />
</x-site.layout>
