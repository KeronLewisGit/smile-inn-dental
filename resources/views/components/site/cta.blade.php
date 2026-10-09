@props(['title' => 'Start your Smile Inn journey', 'text' => 'Book online in under a minute, or call us and we will find you a time. New patients are always welcome.', 'image' => 'images/clinic/doctors.jpg'])
<section class="wrap">
    <div class="relative overflow-hidden rounded-[2.5rem] bg-ink text-ivory">
        <img src="{{ asset($image) }}" alt="" class="absolute inset-0 h-full w-full object-cover opacity-30" loading="lazy">
        <div class="absolute inset-0 bg-gradient-to-r from-ink via-ink/85 to-ink/40"></div>
        <div class="relative grid gap-8 px-8 py-14 sm:px-14 sm:py-20 lg:grid-cols-2 lg:items-center">
            <div><p class="eyebrow text-gold">Ready when you are</p><h2 class="h-section mt-4 text-ivory">{!! $title !!}</h2><p class="mt-5 max-w-lg text-ivory/75">{{ $text }}</p></div>
            <div class="flex flex-wrap gap-3 lg:justify-end"><a href="{{ route('book') }}" class="btn-gold btn-lg">Book an appointment</a><a href="tel:{{ config('clinic.phone_href') }}" class="btn-lg btn bg-white/10 text-ivory ring-1 ring-white/20 hover:bg-white hover:text-ink"><x-icon name="phone" class="size-4" />{{ config('clinic.phone') }}</a></div>
        </div>
    </div>
</section>
