<?php

namespace Database\Seeders;

use App\Models\AppointmentType;
use App\Models\Post;
use App\Models\Service;
use App\Models\TeamMember;
use App\Models\Testimonial;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

/** All public content of the Smile Inn site. Everything on the live site is represented here and editable in Admin. */
class ContentSeeder extends Seeder
{
    public function run(): void
    {
        $this->team();
        $this->services();
        $this->appointmentTypes();
        $this->testimonials();
        $this->posts();
    }

    private function team(): void
    {
        $team = [
            ['name' => 'Dr. Shenilee Hazell', 'title' => 'Lead Cosmetic, General & Family Dentist', 'photo' => 'images/team/shenilee-hazell.jpg', 'is_dentist' => true, 'accepts_bookings' => true, 'instagram' => 'https://www.instagram.com/smileinntt/',
                'bio' => "Founder of Smile Inn Dental and the dentist behind the Caribbean's first Emerald-certified Invisalign clinic. Dr. Hazell built Smile Inn in 2019 around one idea: that a dental visit can be precise, gentle and genuinely enjoyable. She leads the cosmetic and Invisalign work, from digital smile design to the final polish, and is known for putting nervous patients completely at ease."],
            ['name' => 'Dr. Ivana Singh', 'title' => 'Associate Dentist', 'photo' => 'images/team/ivana-singh.jpg', 'is_dentist' => true, 'accepts_bookings' => true,
                'bio' => 'Dr. Singh provides general and restorative care with a calm, thorough approach. Patients value how clearly she explains each step before anything happens.'],
            ['name' => 'Dr. Amy Leigh Teixeira', 'title' => 'Associate Dentist', 'photo' => 'images/team/amy-leigh-teixeira.jpg', 'is_dentist' => true, 'accepts_bookings' => true,
                'bio' => 'Dr. Teixeira focuses on preventive and family dentistry, with a gentle touch that makes her a favourite with first-time and anxious patients.'],
            ['name' => 'Dr. Sarikha Ramlochansingh-Prabhudial', 'title' => 'General Dentist', 'photo' => 'images/team/sarikha-ramlochansingh-prabhudial.jpg', 'is_dentist' => true, 'accepts_bookings' => true,
                'bio' => 'Dr. Ramlochansingh-Prabhudial delivers everyday dentistry done well: cleanings, fillings, root canals and extractions, always with comfort first.'],
            ['name' => 'Nicole Durham', 'title' => 'Practice Manager', 'photo' => 'images/team/nicole-durham.jpg',
                'bio' => 'Nicole keeps the clinic running smoothly and is the friendly voice that greets you on the phone and at the front desk.'],
            ['name' => 'Jayva Grant', 'title' => 'Head Nurse / Myofunctional Therapist', 'photo' => 'images/team/jayva-grant.jpg',
                'bio' => 'Jayva leads our nursing team and runs the myofunctional therapy programme, helping patients improve breathing, swallowing and jaw alignment through guided exercises. She is also the one behind the iTero scanner during your Invisalign consult.'],
            ['name' => 'Atasia Barzey', 'title' => 'Hygienist', 'photo' => 'images/team/atasia-barzey.jpg', 'accepts_bookings' => true,
                'bio' => 'Atasia takes care of cleanings and stain removal, and will show you exactly how to keep your smile at its best between visits.'],
        ];
        foreach ($team as $i => $m) {
            TeamMember::updateOrCreate(['slug' => Str::slug($m['name'])], $m + ['sort' => $i + 1, 'is_dentist' => false, 'accepts_bookings' => false, 'active' => true]);
        }
    }

    private function services(): void
    {
        $services = [
            [
                'name' => 'Invisalign Teeth Straightening', 'slug' => 'invisalign', 'icon' => 'aligner', 'image' => 'images/clinic/invisalign-aligner.jpg',
                'tagline' => "The Caribbean's top Invisalign clinic, Emerald certified.",
                'intro' => "Many people want straighter teeth, but they don't want the ugly metal brackets and the discomfort that comes with traditional orthodontic work. Invisalign uses a series of clear, nearly invisible aligners to correct overcrowded, misaligned and crooked teeth.",
                'body' => "Sit back while a trained nurse digitally scans your mouth with the iTero 5D scanner, the preferred scanner worldwide. In seconds you see a 3D image of your smile and a preview of where it is going. Your dentist reviews your concerns and course of treatment, and if you are a candidate an Invisalign-certified dentist creates your plan and custom trays are made.\n\nMost treatments take around 12 to 18 months, with visible results far sooner. You wear your aligners about 22 hours a day, change them every 7 to 14 days, and visit us for progress checks along the way.",
                'treatments' => [
                    ['name' => 'Free Invisalign consultation', 'description' => 'An iTero 5D scan, a smile preview and an honest conversation about whether Invisalign is right for you. No commitment.'],
                    ['name' => 'Invisalign Full', 'description' => 'Comprehensive straightening for crowding, spacing, overbites, underbites and crossbites.'],
                    ['name' => 'Invisalign Lite and Express', 'description' => 'Shorter courses for minor crowding or relapse after previous orthodontics.'],
                    ['name' => 'Invisalign Teen', 'description' => 'Designed for growing smiles, with compliance indicators and room for erupting teeth.'],
                    ['name' => 'Dental Monitoring', 'description' => 'Virtual check-ins from your phone between visits, so progress never waits for an appointment.'],
                    ['name' => 'Retainers', 'description' => 'Custom retainers to keep your new smile exactly where it should be.'],
                ],
                'faqs' => [
                    ['q' => 'What is Invisalign?', 'a' => 'A series of custom-made clear aligners, each worn for one to two weeks, that gradually move your teeth into place. Your provider sets the treatment length.'],
                    ['q' => 'How much does it cost?', 'a' => 'Cost depends on the complexity of your case and is roughly comparable to traditional fixed braces. Your free consultation includes a full quote.'],
                    ['q' => 'Is it painful?', 'a' => 'Some pressure or mild discomfort is common for a few days at the start of each new aligner. That is normal, it means the aligner is working, and it usually fades within a couple of days.'],
                    ['q' => 'How long does it take?', 'a' => 'Typically 6 to 18 months depending on complexity. Minor cases can finish in as little as three months. We confirm your timeline at the consultation.'],
                ],
                'featured' => true,
            ],
            [
                'name' => 'General Dentistry', 'slug' => 'general-dentistry', 'icon' => 'tooth', 'image' => 'images/clinic/moment-2.webp',
                'tagline' => 'Gentle dentistry for a healthy mouth.',
                'intro' => 'General dentistry aims to ensure that your mouth is free from any oral disease or pathology and ensure a healthy mouth.',
                'body' => 'Every visit starts with a comprehensive evaluation, and every treatment plan is explained before we begin. From cleanings and fillings to root canals, dentures and airway dentistry, our general care is thorough, modern and gentle.',
                'treatments' => [
                    ['name' => 'Fillings', 'description' => 'Mainly used to fill cavities, and also to repair grinding damage or replace part of a broken tooth.'],
                    ['name' => 'Cleanings', 'description' => 'A professional cleaning removes plaque and tartar that brushing alone cannot.'],
                    ['name' => 'Root canal treatments', 'description' => 'Removes the nerve from an infected tooth to relieve pain caused by decay or infection in the pulp.'],
                    ['name' => 'Extractions', 'description' => 'Removes a tooth completely from the jawbone when needed for your oral health.'],
                    ['name' => 'Surgical extractions', 'description' => 'For hard-to-reach or complicated teeth, such as impacted wisdom teeth.'],
                    ['name' => 'Gum disease treatment', 'description' => 'Options depend on the stage of disease, past treatment response and overall health. We start with a full assessment.'],
                    ['name' => 'Comprehensive oral evaluations', 'description' => 'We check for signs of cancer or precancerous conditions, because early detection matters.'],
                    ['name' => 'Preventative treatments', 'description' => 'After a general consultation we set you up with best practices and prevention methods.'],
                    ['name' => 'Fluoride treatments', 'description' => 'A quick, painless treatment that strengthens teeth against decay. For children and adults.'],
                    ['name' => 'Replacing missing teeth', 'description' => 'Several replacement options to restore chewing, speaking and confidence.'],
                    ['name' => 'Dental implants', 'description' => 'Now offered at Smile Inn: a permanent, natural-looking replacement for missing teeth.'],
                    ['name' => 'Dentures', 'description' => 'Custom full or partial dentures that restore function and appearance.'],
                    ['name' => 'Bridges', 'description' => 'Fixed restorations anchored to natural teeth or implants to replace missing teeth.'],
                    ['name' => 'Onlays and inlays', 'description' => 'Custom restorations that preserve tooth structure. Inlays fit in the grooves, onlays cover the cusps.'],
                    ['name' => 'Oral surgery', 'description' => 'Extractions, implants, wisdom tooth removal and jaw surgery, with careful pre- and post-operative instructions.'],
                    ['name' => 'Bruxism treatment', 'description' => 'Manages teeth grinding and clenching, often with a custom-fitted night guard.'],
                    ['name' => 'Snoring treatments', 'description' => 'Custom oral appliances that help keep the airway open during sleep.'],
                    ['name' => 'Myofunctional therapy', 'description' => 'Exercises that strengthen tongue, lip and cheek muscles to improve breathing, swallowing and alignment.'],
                    ['name' => 'Airway dentistry', 'description' => 'Diagnoses and treats restricted airflow that affects sleep and health, including jaw alignment and nasal breathing.'],
                    ['name' => 'Mouthguards and sports guards', 'description' => 'Custom devices that protect teeth from impact during sport or from grinding damage.'],
                    ['name' => 'Oral sedation', 'description' => 'A pill taken before your appointment to help you relax. Ideal for dental anxiety.'],
                    ['name' => 'Desensitizing treatment', 'description' => 'Blocks pain signals and strengthens exposed areas to reduce sensitivity to hot, cold or sweet foods.'],
                ],
                'faqs' => [
                    ['q' => 'How often should I come in?', 'a' => 'For most people, a check-up and cleaning every six months keeps small problems from becoming big ones. We will recommend a schedule that fits you.'],
                    ['q' => 'I am nervous about the dentist. Can you help?', 'a' => 'Yes. Tell us when you book. We take things at your pace, explain everything first, and offer oral sedation for those who need it.'],
                ],
                'featured' => true,
            ],
            [
                'name' => 'Cosmetic Dentistry', 'slug' => 'cosmetic-dentistry', 'icon' => 'sparkle', 'image' => 'images/clinic/patient-smiling.jpg',
                'tagline' => 'Smile transformations, designed digitally.',
                'intro' => 'Cosmetic dentistry aims to improve the aesthetic appearance of your smile and results in a more confident you!',
                'body' => 'We plan cosmetic work with Digital Smile Design, so you see the result before we start. Whitening, bonding, contouring, veneers and crowns are all done in-house by the same team that does your check-ups.',
                'treatments' => [
                    ['name' => 'Professional teeth whitening', 'description' => 'Removes intrinsic and extrinsic stains safely, when used as directed.'],
                    ['name' => 'Digital Smile Design (DSD)', 'description' => 'Planning that uses photos, video, 3D scanning and software to analyse your face, jaw and teeth.'],
                    ['name' => 'Composite bonding', 'description' => 'Tooth-coloured resin to repair chips, cracks or gaps and improve discoloured teeth. A quick, minimally invasive solution.'],
                    ['name' => 'Teeth contouring', 'description' => 'Also called enameloplasty or tooth reshaping, for minor imperfections.'],
                    ['name' => 'Enamel contouring', 'description' => 'Smooths and shapes tooth edges to correct small imperfections.'],
                    ['name' => 'Veneers', 'description' => 'Thin, tooth-coloured shells bonded to the front of teeth to improve their appearance.'],
                    ['name' => 'Cosmetic crowns', 'description' => 'Porcelain caps that cover a decayed or damaged tooth above the gum line.'],
                    ['name' => 'Stain removal cleaning', 'description' => 'A professional clean that removes surface staining.'],
                    ['name' => 'Gum contouring', 'description' => 'Reshapes the gum line by removing excess tissue or smoothing uneven areas.'],
                    ['name' => 'Laser gum treatments', 'description' => 'Uses lasers to treat gum disease and reshape gum tissue.'],
                    ['name' => 'Clear aligners and Invisalign', 'description' => 'Removable, custom transparent trays that shift teeth without traditional braces.'],
                    ['name' => 'Traditional braces', 'description' => 'Metal brackets and wires with periodic adjustments, when they are the right tool for the job.'],
                    ['name' => 'Bridges and implants', 'description' => 'Fixed restorations that replace one or more missing teeth.'],
                ],
                'faqs' => [
                    ['q' => 'Will it look natural?', 'a' => 'That is the whole point. We design to your face, not a template, and patients tell us the outcome is "so natural".'],
                    ['q' => 'Can I see the result first?', 'a' => 'Yes. With Digital Smile Design and the iTero scanner you preview your new smile before any treatment begins.'],
                ],
                'featured' => true,
            ],
            [
                'name' => "Children's Dentistry", 'slug' => 'childrens-dentistry', 'icon' => 'child', 'image' => 'images/clinic/moment-5.webp',
                'tagline' => 'First visits that end in high-fives.',
                'intro' => 'We know how scary a first visit may be for your children. Our team is trained to approach children in a fun and loving manner so that visits are smooth and pain-free.',
                'body' => 'Child-sized equipment, patient explanations and a lot of encouragement. We focus on prevention, because prevention is better than cure, and help parents judge how much care their child needs.',
                'treatments' => [
                    ['name' => "Children's cleanings", 'description' => "Pain-free cleanings to keep a child's oral health in check."],
                    ['name' => "Children's orthodontic assessment", 'description' => 'Helps parents judge how much dental care their child needs and how to prevent cavities.'],
                    ['name' => 'Fluoride treatments', 'description' => 'A quick, pain-free treatment that strengthens teeth against cavities and decay.'],
                    ['name' => 'Fillings', 'description' => 'For cavities, grinding damage or a broken tooth.'],
                    ['name' => 'Extractions', 'description' => 'When a tooth has to go, we make it as calm as possible.'],
                    ['name' => 'Sports mouth guards', 'description' => 'Protection against blows to the face and head during contact sports.'],
                    ['name' => 'Preventative treatments', 'description' => 'Best practices and prevention methods after a general consultation.'],
                    ['name' => 'Invisalign Teen', 'description' => 'A modern, fully digitised straightening experience for teenagers.'],
                    ['name' => 'Oral sedation', 'description' => 'For very anxious children, a pill before the appointment helps them relax.'],
                    ['name' => 'Desensitizing treatment', 'description' => 'Reduces sensitivity to hot, cold or sweet foods.'],
                ],
                'faqs' => [
                    ['q' => 'When should my child first see a dentist?', 'a' => 'By their first birthday, or within six months of the first tooth appearing. Early visits are short, friendly and mostly about getting comfortable.'],
                    ['q' => 'Can I stay with my child during treatment?', 'a' => 'Of course. Most children do best with a parent nearby.'],
                ],
                'featured' => true,
            ],
            [
                'name' => 'Oral Surgery', 'slug' => 'oral-surgery', 'icon' => 'scalpel', 'image' => 'images/clinic/moment-3.webp',
                'tagline' => 'Extractions and advanced procedures, done with care.',
                'intro' => 'Extractions and more advanced surgical procedures, with your comfort as the first priority.',
                'body' => 'From a straightforward extraction to impacted wisdom teeth and implant placement, we plan every procedure carefully and send you home with clear aftercare instructions.',
                'treatments' => [
                    ['name' => 'Simple and surgical extractions', 'description' => 'Including impacted or hard-to-reach teeth.'],
                    ['name' => 'Wisdom tooth removal', 'description' => 'With aftercare guidance to reduce pain, swelling and dry socket.'],
                    ['name' => 'Dental implants', 'description' => 'Placement and restoration of implants to replace missing teeth.'],
                    ['name' => 'Jaw surgery', 'description' => 'Corrective procedures planned with modern imaging.'],
                    ['name' => 'Oral sedation', 'description' => 'To help you relax through longer procedures.'],
                ],
                'faqs' => [
                    ['q' => 'What should I do after an extraction?', 'a' => 'Bite on gauze, avoid straws and smoking, stick to soft foods and keep the area clean. We give you a full written aftercare sheet.'],
                ],
                'featured' => true,
            ],
            [
                'name' => 'Dental Emergencies', 'slug' => 'emergency', 'icon' => 'alert', 'image' => 'images/clinic/moment-4.webp',
                'tagline' => 'Immediate relief when it cannot wait.',
                'intro' => 'Get immediate relief and expert care for dental emergencies at Smile Inn Dental. Our experienced team provides prompt, compassionate treatment for toothaches, broken teeth and urgent dental needs.',
                'body' => 'Call us the moment something goes wrong. We keep time for emergencies every day we are open, and we will tell you exactly what to do while you are on your way.',
                'treatments' => [
                    ['name' => 'Severe toothache that will not go away', 'description' => 'We find the cause, relieve the pain and treat the tooth.'],
                    ['name' => 'Tooth knocked out completely', 'description' => 'Time is critical. Keep the tooth in milk or saline and come straight in.'],
                    ['name' => 'Cracked or chipped tooth', 'description' => 'From biting something hard or a fall. Bonding, crowns or veneers depending on the damage.'],
                    ['name' => 'Child has fallen and broken a tooth', 'description' => 'Gentle, fast care for little ones.'],
                    ['name' => 'Dental abscess with swelling and pain', 'description' => 'Drained and treated promptly to stop the infection spreading.'],
                    ['name' => 'Swollen, bleeding gums', 'description' => 'Assessed and treated the same day.'],
                    ['name' => 'Object lodged between teeth', 'description' => 'Removed safely. Avoid sharp objects at home.'],
                    ['name' => 'Fallen-out filling with tooth pain', 'description' => 'Replaced to protect the tooth.'],
                    ['name' => 'Loose crown or bridge', 'description' => 'Re-cemented or replaced.'],
                    ['name' => 'Dry socket after extraction', 'description' => 'Dressed and managed to ease the pain.'],
                    ['name' => "Child's loose, painful baby tooth", 'description' => 'Checked and, if needed, removed gently.'],
                    ['name' => 'Swollen, painful jaw', 'description' => 'Diagnosed with imaging and treated.'],
                ],
                'faqs' => [
                    ['q' => 'Do you have emergency hours?', 'a' => 'We see emergencies during our opening hours, Monday to Saturday 8:00 AM to 3:30 PM. Call us first so we can prepare for you.'],
                ],
                'featured' => true,
            ],
        ];
        foreach ($services as $i => $s) {
            Service::updateOrCreate(['slug' => $s['slug']], $s + ['sort' => $i + 1, 'active' => true]);
        }
    }

    private function appointmentTypes(): void
    {
        $hazell = TeamMember::where('slug', 'dr-shenilee-hazell')->first();
        $atasia = TeamMember::where('slug', 'atasia-barzey')->first();
        $types = [
            ['name' => 'General Consultation', 'description' => 'A general consultation with one of our doctors.', 'duration_minutes' => 30],
            ['name' => 'Consultation with Dr. Hazell', 'description' => 'Book a consultation with Dr. Shenilee Hazell.', 'duration_minutes' => 30, 'team_member_id' => $hazell?->id],
            ['name' => 'Hygienist: Cleaning Only', 'description' => 'A professional clean with hygienist Atasia Barzey.', 'duration_minutes' => 45, 'team_member_id' => $atasia?->id],
            ['name' => 'Free Invisalign Consultation', 'description' => 'iTero 5D scan, smile preview and a quote. No commitment.', 'duration_minutes' => 45, 'is_free' => true],
            ['name' => 'Free Virtual Cosmetic Consult', 'description' => 'A free video consultation with Dr. Shenilee Hazell.', 'duration_minutes' => 20, 'is_free' => true, 'is_virtual' => true, 'team_member_id' => $hazell?->id],
            ['name' => 'Emergency Appointment', 'description' => 'Toothache, broken tooth or anything urgent. Call us as well.', 'duration_minutes' => 30],
            ['name' => "Children's Visit", 'description' => 'Check-up and cleaning for the little ones.', 'duration_minutes' => 30],
        ];
        foreach ($types as $i => $t) {
            AppointmentType::updateOrCreate(['slug' => Str::slug($t['name'])], $t + ['sort' => $i + 1, 'active' => true, 'is_free' => false, 'is_virtual' => false, 'team_member_id' => null]);
        }
    }

    private function testimonials(): void
    {
        $t = [
            ['name' => 'Mark Hypolite', 'treatment' => 'General dentistry', 'featured' => true, 'quote' => 'Absolutely outstanding. Like many, I hated dentists, so much pain, so uncomfortable, until Dr. Hazell took over. She is gentle, professional and I actually look forward to my visits now.'],
            ['name' => 'Mershawna Ramnath', 'treatment' => 'Cosmetic dentistry', 'featured' => true, 'quote' => "Amazing dentist! I've never felt more at peace sitting in a dentist's chair. Every procedure was explained, the techniques and finishing were flawless."],
            ['name' => 'Coleen George', 'treatment' => 'Teeth contouring', 'featured' => true, 'quote' => "I had tooth contouring done with Dr. Hazell. It was short, painless and affordable. Now I'm totally obsessed with my new smile."],
            ['name' => 'Giselle Aird', 'treatment' => "Children's dentistry", 'featured' => true, 'quote' => 'My son who is 8 and I are both patients of this amazing dentist. She is great with children and the clinic has some of the latest equipment.'],
            ['name' => 'Chioke Herbert', 'treatment' => 'Cosmetic dentistry', 'quote' => "Smile Inn dentistry is nothing like you've ever experienced before. The outcome is so natural."],
            ['name' => 'Natasha Laurent Wheeler', 'treatment' => 'General dentistry', 'quote' => 'It was a really nice experience. The staff was really nice and friendly, I felt really welcomed from the moment I walked in.'],
            ['name' => 'Alicia Leplatte', 'treatment' => 'General dentistry', 'quote' => 'This was an awesome and professional experience. Their customer service gets a 10 out of 10 from me.'],
            ['name' => 'Dale Biddy', 'treatment' => "Children's dentistry", 'quote' => 'My son who is 8 years and myself are both patients of this amazing dentist. We would not go anywhere else.'],
            ['name' => 'Wall of Love', 'treatment' => 'The experience', 'quote' => 'From the moment you arrive everything is absolute perfection. The service is phenomenal, the ambience is so soothing, and the results speak for themselves.'],
            ['name' => 'Miriam', 'treatment' => 'Invisalign', 'video_url' => 'https://www.instagram.com/smileinntt/', 'quote' => 'Watch Miriam share her Invisalign journey on Instagram.'],
            ['name' => 'Abigail', 'treatment' => 'Invisalign', 'video_url' => 'https://www.instagram.com/smileinntt/', 'quote' => 'Watch Abigail talk about her new smile on Instagram.'],
            ['name' => 'Aneesa', 'treatment' => 'Cosmetic dentistry', 'video_url' => 'https://www.instagram.com/smileinntt/', 'quote' => 'Watch Aneesa share her Smile Inn experience on Instagram.'],
        ];
        foreach ($t as $i => $row) {
            Testimonial::updateOrCreate(['name' => $row['name']], $row + ['sort' => $i + 1, 'rating' => 5, 'source' => 'Google', 'featured' => false, 'active' => true]);
        }
    }

    private function posts(): void
    {
        $full = [
            ['title' => 'Can Bacteria From Your Mouth Reach Your Lungs?', 'category' => 'Education', 'cover_image' => 'images/blog/bacteria-lungs.png', 'featured' => true, 'days' => 3,
                'excerpt' => 'Most oral bacteria are harmless when they stay balanced. Gum disease changes that, and the lungs can pay the price.',
                'body' => '<h2>How bacteria from the mouth can reach the lungs</h2><p>Your mouth is home to hundreds of species of bacteria. Most are harmless while they stay in balance, but gum disease or poor oral hygiene lets harmful species multiply. Those bacteria can reach your lungs in three ways: by being breathed in, by inhaling saliva during sleep, or by slipping into the airway after being swallowed, a process called aspiration.</p><h2>The link between gum disease and lung infections</h2><p>Researchers have detected the same bacteria that cause gum disease in the lungs of people with pneumonia and COPD. Once there, they can add to inflammation and infection.</p><h2>Why gum disease increases the risk</h2><p>The risk is highest for older adults, people with weakened immune systems, anyone with chronic lung disease, and those who struggle to maintain oral hygiene.</p><h2>The importance of good oral hygiene</h2><p>Daily brushing and flossing, regular check-ups and professional cleanings keep bacterial levels under control and lower the risk of gum disease.</p><h2>Oral health is connected to overall health</h2><p>Not every respiratory infection starts in the mouth, but healthy teeth and gums play a real role in reducing the risk. If your gums bleed or you have not had a cleaning in a while, book one.</p>'],
            ['title' => 'How Acid Reflux Can Affect Your Teeth', 'category' => 'Education', 'cover_image' => 'images/blog/acid-reflux.png', 'featured' => true, 'days' => 10,
                'excerpt' => 'Reflux is usually treated as a stomach problem. Your enamel sees it differently.',
                'body' => '<h2>What is acid reflux?</h2><p>Acid reflux is when stomach acid travels up into the oesophagus and sometimes reaches the mouth. Warning signs include a sour taste, frequent belching, heartburn and acid rising into the throat.</p><h2>How acid reflux damages teeth</h2><p>Even occasional episodes expose teeth to acid. Over time the acid draws minerals out of the enamel, a process called demineralisation. Enamel thins, teeth become sensitive, weak spots form and cavities are more likely.</p><h2>Why we take acid reflux seriously in dentistry</h2><p>Left alone, early erosion can progress to deep decay, cracked teeth, infections that need root canals, or extraction.</p><h2>Protecting your teeth if you have acid reflux</h2><p>Routine exams, monitoring for erosion, professional cleanings and early detection of decay all help. Tell us about reflux symptoms at your visit so we can watch for the signs.</p><h2>Why dental X-rays are important</h2><p>X-rays show cavities between teeth and structural changes hidden below the surface that we cannot see by eye.</p><h2>The goal: keeping your teeth healthy for life</h2><p>Early attention preserves natural teeth for decades. That is always the aim.</p>'],
            ['title' => '10 Problems Associated With Improper Tooth Alignment', 'category' => 'Education', 'cover_image' => 'images/blog/tooth-alignment.png', 'featured' => true, 'days' => 17,
                'excerpt' => 'Crooked teeth are often seen as cosmetic. The effects on your gums, enamel and bite are anything but.',
                'body' => '<p>Misaligned teeth change how your teeth meet, how chewing pressure is spread, and how easily your mouth can be cleaned. Here are ten problems that follow.</p><h2>1. Receding gums</h2><p>Uneven pressure pushes gums back, exposing roots to sensitivity and decay.</p><h2>2. Abfractions</h2><p>Small wedge-shaped notches near the gumline caused by flexing under uneven load.</p><h2>3. Excessive wearing of teeth</h2><p>Uneven grinding wears enamel, leaving teeth shorter, more sensitive and prone to chipping.</p><h2>4. Periodontal pocketing</h2><p>Crowded teeth trap plaque, which forms pockets between tooth and gum.</p><h2>5. Difficulty brushing and flossing</h2><p>Overlaps are simply harder to clean.</p><h2>6. Increased plaque levels</h2><p>Which follows directly from point five.</p><h2>7. Tooth decay</h2><p>More plaque, more acid, more cavities.</p><h2>8. Difficulty eating</h2><p>An unbalanced bite makes chewing uncomfortable.</p><h2>9. Gingivitis</h2><p>Inflamed gums are the first stage of gum disease.</p><h2>10. Tooth shifting over time</h2><p>Teeth keep drifting, so the problem gets worse, not better.</p><h2>Why tooth alignment matters for long-term oral health</h2><p>Addressing alignment early protects both the health and the function of your teeth. If any of this sounds familiar, a free Invisalign consultation is a good place to start.</p>'],
        ];
        foreach ($full as $i => $p) {
            $days = $p['days'];
            unset($p['days']);
            Post::updateOrCreate(['slug' => Str::slug($p['title'])], $p + ['published_at' => now()->subDays($days)]);
        }

        // The rest of the live blog archive: titles and categories, with a short standfirst so each page reads properly until the full article is pasted in Admin.
        $archive = [
            ['How Long Does It Take to Get Invisalign in Trinidad & Tobago?', 'Invisalign', 'From your free consultation to your first set of aligners, here is the realistic timeline at Smile Inn.'],
            ['The Truth About Tooth Gaps (Diastemas)', 'Education', 'Some gaps are harmless, some are a sign of something else. How we tell the difference.'],
            ['What Is Airway Dentistry? How Your Dentist May Be Seeing More Than Just Your Teeth', 'Education', 'Mouth breathing, snoring and jaw shape are all clues your dentist can read.'],
            ['Wisdom Tooth Removal Aftercare: Tips to Reduce Pain, Swelling & Dry Socket', 'Education', 'What to eat, what to avoid, and when to call us after wisdom tooth surgery.'],
            ['Why Bleeding Gums Are Never "Just a Little Blood"', 'Education', 'Healthy gums do not bleed. What bleeding is telling you and what to do about it.'],
            ['The Different Types of Dental X-Rays Explained', 'Education', 'Bitewing, periapical, panoramic and 3D: what each one shows and why we take them.'],
            ['Coffee vs Energy Drinks: Which One Stains Your Teeth More?', 'Education', 'The answer surprised a few of our patients.'],
            ['Canker Sores: What Your Mouth Is Trying to Tell You', 'Education', 'Causes, triggers and when a sore needs a second look.'],
            ['Why I Chose Invisalign Over Braces: A Real Patient Experience', 'Invisalign', 'One patient explains the decision, the first week, and the result.'],
            ['What Is Dental Bonding? A Quick, Minimally Invasive Way to Improve Your Smile', 'Education', 'Chips, gaps and discolouration fixed in a single visit.'],
            ["5 Things You Can Do If You're Prone to Getting Cavities", 'Education', 'Practical habits that make a measurable difference.'],
            ["Smile Inn Is Now Offering Dental Implants: Here's the Full Process (and Timeline)", 'Cosmetic', 'Consultation, placement, healing and the final crown, step by step.'],
            ['Vaping Is Quietly Destroying Your Smile', 'Education', 'Dry mouth, gum disease and staining: what we see in vapers.'],
            ['Why Dentists and Patients Hate Each Other, and How We Fix It', 'Education', 'The honest version of why dental visits feel bad, and how Smile Inn does it differently.'],
            ['Why Your Invisalign Trays Smell After a Night of Partying, and What You Can Do About It', 'Education', 'Yes, we have been asked. Here is the fix.'],
            ['Can Your Wisdom Teeth Really Cause Headaches?', 'Education', 'Sometimes. Here is how to tell.'],
            ['What Dental Treatments Are Safe During Pregnancy?', 'Education', 'Most of them. What we do, what we delay, and why cleanings matter more than ever.'],
            ["Burning Mouth Syndrome and Menopause: Why Your Mouth Feels Like It's on Fire", 'Education', 'A real condition with real help available.'],
            ["Can You Get Cavities from Kissing? Let's Talk About It.", 'Education', 'Cavity-causing bacteria do travel. What that means in practice.'],
            ['The Hidden Link Between Your Mouth and Lungs', 'Education', 'Oral bacteria and respiratory health, explained.'],
            ['The Science Behind Creating a Perfect Smile', 'Education', 'Proportions, symmetry and Digital Smile Design.'],
            ['A New Era in Digital Dentistry', 'Launches', 'Introducing the iTero 5D scanner at Smile Inn.'],
            ['Bad Breath', 'Education', 'Causes you can fix at home, and the ones you cannot.'],
            ['The Honest Tooth', 'Education', 'Straight talk on the questions patients are afraid to ask.'],
            ['7 Ways to Improve Your Smile', 'Cosmetic', 'From whitening to contouring, ranked by how quickly you see results.'],
            ['What to Do If You Lose Your Invisalign', 'Invisalign', 'Do not panic. Do this instead.'],
            ['12 Facts About Oral Hygiene You May Not Know', 'Education', 'A few of these will change how you brush.'],
            ['Gum Disease', 'Education', 'Stages, symptoms and treatment options.'],
            ['Composite Bonding', 'Cosmetic', 'A quick, minimally invasive cosmetic fix.'],
            ['3 Dental Signs That Are Not Normal', 'Education', 'If you notice any of these, book sooner rather than later.'],
            ['5 Dental Practices to Prevent Cavities', 'Education', 'Simple, daily and effective.'],
            ['My Favorite Aligner Hygiene Products', 'Invisalign', "Dr. Hazell's picks for keeping trays fresh."],
            ['Embracing and Enhancing Diastemas', 'Education', 'Not every gap needs closing. Some just need framing.'],
            ['My Top 5 Must-Haves for Your Invisalign Journey', 'Invisalign', 'The small things that make 22 hours a day easy.'],
            ['Tooth Whitening', 'Cosmetic', 'In-clinic versus at-home, and what actually works.'],
        ];
        foreach ($archive as $i => [$title, $category, $excerpt]) {
            Post::firstOrCreate(['slug' => Str::slug($title)], ['title' => $title, 'category' => $category, 'excerpt' => $excerpt, 'body' => '<p>'.$excerpt.'</p><p>Want the full story? Book a visit or send us a message and we will walk you through it in person.</p>', 'published_at' => now()->subDays(30 + $i * 9)]);
        }
    }
}
