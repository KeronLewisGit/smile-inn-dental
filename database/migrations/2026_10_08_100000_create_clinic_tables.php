<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $t) {
            $t->string('role', 12)->default('staff')->after('password'); // admin | staff
        });

        Schema::create('settings', function (Blueprint $t) {
            $t->string('key')->primary();
            $t->json('value')->nullable();
            $t->timestamps();
        });

        Schema::create('team_members', function (Blueprint $t) {
            $t->id();
            $t->string('name', 120);
            $t->string('slug', 140)->unique();
            $t->string('title', 160);
            $t->text('bio')->nullable();
            $t->string('photo')->nullable();
            $t->string('instagram')->nullable();
            $t->boolean('is_dentist')->default(false);
            $t->boolean('accepts_bookings')->default(false);
            $t->boolean('active')->default(true);
            $t->unsignedSmallInteger('sort')->default(0);
            $t->timestamps();
        });

        Schema::create('services', function (Blueprint $t) {
            $t->id();
            $t->string('name', 120);
            $t->string('slug', 140)->unique();
            $t->string('tagline', 200)->nullable();
            $t->text('intro')->nullable();
            $t->text('body')->nullable();
            $t->string('icon', 40)->nullable();
            $t->string('image')->nullable();
            $t->json('treatments')->nullable();   // [{name, description}]
            $t->json('faqs')->nullable();         // [{q, a}]
            $t->boolean('featured')->default(true);
            $t->boolean('active')->default(true);
            $t->unsignedSmallInteger('sort')->default(0);
            $t->timestamps();
        });

        Schema::create('appointment_types', function (Blueprint $t) {
            $t->id();
            $t->string('name', 120);
            $t->string('slug', 140)->unique();
            $t->string('description', 255)->nullable();
            $t->unsignedSmallInteger('duration_minutes')->default(30);
            $t->boolean('is_free')->default(false);
            $t->boolean('is_virtual')->default(false);
            $t->foreignId('team_member_id')->nullable()->constrained()->nullOnDelete(); // fixed provider, e.g. Dr Hazell only
            $t->boolean('active')->default(true);
            $t->unsignedSmallInteger('sort')->default(0);
            $t->timestamps();
        });

        Schema::create('patients', function (Blueprint $t) {
            $t->id();
            $t->string('first_name', 80);
            $t->string('last_name', 80);
            $t->string('email', 160)->nullable()->index();
            $t->string('phone', 40)->nullable()->index();
            $t->date('date_of_birth')->nullable();
            $t->string('gender', 20)->nullable();
            $t->string('source', 20)->default('website'); // website | admin | import
            $t->text('notes')->nullable();
            $t->boolean('marketing_opt_in')->default(false);
            $t->timestamps();
        });

        Schema::create('appointments', function (Blueprint $t) {
            $t->id();
            $t->string('reference', 20)->unique();
            $t->foreignId('patient_id')->constrained()->cascadeOnDelete();
            $t->foreignId('appointment_type_id')->nullable()->constrained()->nullOnDelete();
            $t->foreignId('team_member_id')->nullable()->constrained()->nullOnDelete();
            $t->dateTime('starts_at')->index();
            $t->dateTime('ends_at');
            $t->string('status', 12)->default('pending'); // pending | confirmed | completed | cancelled | no_show
            $t->text('patient_notes')->nullable();
            $t->text('staff_notes')->nullable();
            $t->string('source', 20)->default('website');
            $t->string('manage_token', 64)->unique();
            $t->timestamp('confirmed_at')->nullable();
            $t->timestamp('cancelled_at')->nullable();
            $t->string('cancellation_reason', 255)->nullable();
            $t->timestamp('reminded_at')->nullable();
            $t->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $t->timestamps();
            $t->index(['status', 'starts_at']);
        });

        Schema::create('inquiries', function (Blueprint $t) {
            $t->id();
            $t->foreignId('patient_id')->nullable()->constrained()->nullOnDelete();
            $t->string('name', 160);
            $t->string('email', 160)->nullable();
            $t->string('phone', 40)->nullable();
            $t->string('service', 80)->nullable();
            $t->string('gender', 20)->nullable();
            $t->text('message')->nullable();
            $t->string('status', 12)->default('new'); // new | contacted | booked | closed
            $t->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $t->text('staff_notes')->nullable();
            $t->timestamps();
            $t->index(['status', 'created_at']);
        });

        Schema::create('subscribers', function (Blueprint $t) {
            $t->id();
            $t->string('email', 160)->unique();
            $t->string('name', 120)->nullable();
            $t->timestamp('unsubscribed_at')->nullable();
            $t->timestamps();
        });

        Schema::create('testimonials', function (Blueprint $t) {
            $t->id();
            $t->string('name', 120);
            $t->text('quote')->nullable();
            $t->string('treatment', 80)->nullable();
            $t->unsignedTinyInteger('rating')->default(5);
            $t->string('source', 40)->nullable();
            $t->string('video_url')->nullable();
            $t->boolean('featured')->default(false);
            $t->boolean('active')->default(true);
            $t->unsignedSmallInteger('sort')->default(0);
            $t->timestamps();
        });

        Schema::create('posts', function (Blueprint $t) {
            $t->id();
            $t->string('title', 200);
            $t->string('slug', 220)->unique();
            $t->string('category', 40)->default('Education');
            $t->text('excerpt')->nullable();
            $t->longText('body')->nullable();
            $t->string('cover_image')->nullable();
            $t->timestamp('published_at')->nullable()->index();
            $t->foreignId('author_id')->nullable()->constrained('users')->nullOnDelete();
            $t->boolean('featured')->default(false);
            $t->timestamps();
        });
    }

    public function down(): void
    {
        foreach (['posts', 'testimonials', 'subscribers', 'inquiries', 'appointments', 'patients', 'appointment_types', 'services', 'team_members', 'settings'] as $table) {
            Schema::dropIfExists($table);
        }
        Schema::table('users', fn (Blueprint $t) => $t->dropColumn('role'));
    }
};
