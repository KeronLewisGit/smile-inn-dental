<?php

// Clinic details. Anything here can be overridden from Admin → Settings (stored in the settings table).
return [
    'name' => 'Smile Inn Dental',
    'short' => 'Smile Inn',
    'tagline' => 'Precision care, beautiful smiles.',
    'strap' => 'The Dental Experience',
    'founded' => 2019,
    'phone' => env('CLINIC_PHONE', '+1 868-241-3688'),
    'phone_href' => env('CLINIC_PHONE_HREF', '+18682413688'),
    'whatsapp' => env('CLINIC_WHATSAPP', '18682413688'),
    'email' => env('CLINIC_EMAIL', 'smileinntt@gmail.com'),
    'address' => ['line1' => '#24 Mucurapo Road', 'line2' => 'St. James, Port of Spain', 'country' => 'Trinidad and Tobago'],
    'map_url' => 'https://www.google.com/maps/search/?api=1&query=Smile+Inn+Dental+24+Mucurapo+Road+St+James',
    'review_url' => 'https://www.google.com/search?q=smile+inn+dental+reviews',
    'external_booking_url' => 'https://smileinndental.asprodental.com/online-scheduling-portal',
    'virtual_consult_url' => 'https://engage.dental-monitoring.com',
    'social' => [
        'instagram' => 'https://www.instagram.com/smileinntt/',
        'tiktok' => 'https://www.tiktok.com/@smileinntt',
        'x' => 'https://x.com/smileinntt',
    ],
    'handle' => '@smileinntt',
    'instagram_followers' => '25K+',
    // Opening hours: null = closed. Times are 24h, local (America/Port_of_Spain).
    'hours' => [
        'mon' => ['08:00', '15:30'], 'tue' => ['08:00', '15:30'], 'wed' => ['08:00', '15:30'], 'thu' => ['08:00', '15:30'], 'fri' => ['08:00', '15:30'],
        'sat' => ['08:00', '15:30'], 'sun' => null,
    ],
    'booking' => [
        'slot_minutes' => 30,          // granularity of the slot picker
        'chairs' => 3,                 // concurrent appointments the clinic can take per slot
        'lead_hours' => 2,             // earliest a slot can be booked ahead of now
        'horizon_days' => 60,          // how far ahead the calendar opens
        'notify' => env('CLINIC_NOTIFY_EMAIL', 'smileinntt@gmail.com'),
    ],
    'stats' => ['years' => '12+', 'specialists' => '10+', 'smiles' => '100+', 'founded' => '2019'],
    'press' => [
        ['name' => 'Trinidad Express', 'image' => 'images/press/express.webp'],
        ['name' => 'CNC3', 'image' => 'images/press/cnc3.png'],
        ['name' => 'Guardian Trinidad', 'image' => 'images/press/guardian.png'],
        ['name' => 'Dentistry Today', 'image' => 'images/press/dentistry-today.png'],
    ],
    'invisalign_tiers' => [
        ['name' => 'Gold', 'image' => 'images/badges/gold.png'],
        ['name' => 'Platinum Elite', 'image' => 'images/badges/platinum-elite.png'],
        ['name' => 'Diamond', 'image' => 'images/badges/diamond.png'],
        ['name' => 'Diamond Elite', 'image' => 'images/badges/diamond-elite.png'],
        ['name' => 'Emerald', 'image' => 'images/badges/emerald.png'],
    ],
    'interests' => ['Invisalign', 'General Dentistry', 'Cosmetic Dentistry', "Children's Dentistry", 'Oral Surgery', 'Emergency', 'Something else'],
];
