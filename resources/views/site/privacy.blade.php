<x-site.layout title="Privacy Policy">
<section class="wrap max-w-3xl py-16 lg:py-24">
    <p class="eyebrow">Legal</p><h1 class="h-display mt-5">Privacy policy</h1>
    <div class="prose-site mt-10">
        <p>Smile Inn Dental ("we") respects your privacy. This policy explains what we collect through this website and how we use it.</p>
        <h2>What we collect</h2><p>When you book an appointment, send a message or subscribe to our newsletter we collect the details you give us: your name, email address, phone number and anything you write in the message or notes field. We also keep a record of the appointments you book with us.</p>
        <h2>How we use it</h2><p>To manage your appointments, to reply to your enquiries, to send you appointment confirmations and reminders, and, only if you opted in, occasional clinic news. We never sell your information.</p>
        <h2>Who sees it</h2><p>Our clinical and front-desk team, and the service providers that run our website hosting and email. Your clinical records are held separately in our practice management system.</p>
        <h2>Your choices</h2><p>You can ask us to update or delete your website account details at any time by emailing {{ config('clinic.email') }}. Every newsletter contains an unsubscribe link.</p>
        <h2>Cookies</h2><p>This site uses only the cookies it needs to work, such as a session cookie for forms. We do not use advertising trackers.</p>
        <p class="text-sm text-stone">Last updated {{ now()->format('F Y') }}.</p>
    </div>
</section>
</x-site.layout>
