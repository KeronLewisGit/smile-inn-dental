<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Site content (team, services, booking types, testimonials, posts). Safe to re-run: it updates by slug/name.
        $this->call([ContentSeeder::class]);

        if (app()->environment('local')) {
            User::updateOrCreate(['email' => 'admin@smileinn.test'], ['name' => 'Smile Inn Admin', 'password' => 'password', 'role' => 'admin']);
            $this->call([DemoSeeder::class]);
        }
    }
}
