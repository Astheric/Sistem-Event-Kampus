<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('organizations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('logo')->nullable();
            $table->string('contact')->nullable();
            $table->timestamps();
        });

        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('icon')->nullable();
            $table->timestamps();
        });

        Schema::create('venues', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('address');
            $table->string('city');
            $table->text('maps_url')->nullable();
            $table->timestamps();
        });

        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->foreignId('venue_id')->nullable()->constrained()->nullOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('banner')->nullable();
            $table->longText('description');
            $table->dateTime('start_time');
            $table->dateTime('end_time');
            $table->integer('capacity')->default(0);
            $table->string('status')->default('DRAFT'); // DRAFT, SUBMITTED, UNDER REVIEW, APPROVED, REJECTED, PUBLISHED, ONGOING, COMPLETED, CANCELLED
            $table->text('rejection_reason')->nullable();
            $table->timestamps();
        });

        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->decimal('price', 12, 2)->default(0);
            $table->integer('quota')->default(0);
            $table->integer('available_quota')->default(0);
            $table->dateTime('start_sale');
            $table->dateTime('end_sale');
            $table->timestamps();
        });

        Schema::create('event_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('committee_role')->default('Registration'); // Registration, Finance, Documentation
            $table->timestamps();
            $table->unique(['event_id', 'user_id']);
        });

        Schema::create('registrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('event_id')->constrained()->cascadeOnDelete();
            $table->string('registration_code')->unique(); // EVT-2026-XXXXXX
            $table->string('status')->default('PENDING'); // PENDING, CONFIRMED, CANCELLED, ATTENDED, COMPLETED
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->unique(['event_id', 'user_id']); // BR-01: 1 participant cannot register multiple times for same event
        });

        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('registration_id')->constrained()->cascadeOnDelete();
            $table->string('payment_method'); // Bank Transfer, E-Wallet, Cash
            $table->decimal('amount', 12, 2)->default(0);
            $table->string('proof_file')->nullable();
            $table->string('status')->default('PENDING'); // PENDING, VERIFIED, REJECTED
            $table->timestamp('verified_at')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('checkins', function (Blueprint $table) {
            $table->id();
            $table->foreignId('registration_id')->constrained()->cascadeOnDelete();
            $table->foreignId('scanned_by')->constrained('users')->cascadeOnDelete();
            $table->timestamp('checked_in_at');
            $table->timestamps();
            $table->unique('registration_id'); // BR-03: Single check-in per registration
        });

        Schema::create('certificates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('registration_id')->constrained()->cascadeOnDelete();
            $table->string('certificate_number')->unique(); // CERT-2026-XXXXXX
            $table->string('pdf_path')->nullable();
            $table->timestamp('issued_at');
            $table->timestamps();
            $table->unique('registration_id'); // 1 certificate per registration
        });

        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->unsignedTinyInteger('rating'); // 1-5
            $table->text('comment')->nullable();
            $table->timestamps();
            $table->unique(['event_id', 'user_id']);
        });

        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->text('message');
            $table->string('link')->nullable();
            $table->boolean('is_read')->default(false);
            $table->timestamps();
        });

        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('action');
            $table->string('entity_type');
            $table->unsignedBigInteger('entity_id')->nullable();
            $table->string('ip_address')->nullable();
            $table->json('details')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
        Schema::dropIfExists('notifications');
        Schema::dropIfExists('reviews');
        Schema::dropIfExists('certificates');
        Schema::dropIfExists('checkins');
        Schema::dropIfExists('payments');
        Schema::dropIfExists('registrations');
        Schema::dropIfExists('event_members');
        Schema::dropIfExists('tickets');
        Schema::dropIfExists('events');
        Schema::dropIfExists('venues');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('organizations');
    }
};
