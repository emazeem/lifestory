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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('fname');
            $table->string('lname');
            $table->string('role')->default(\Role::Customer);
            $table->string('email');
            $table->string('contact')->nullable();
            $table->string('profile')->nullable();
            $table->string('created_for')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('stripe_customer_id')->nullable();
            $table->string('disabled')->nullable();
            $table->boolean('is_active')->default(0);
            $table->boolean('show_notification')->default(1);
            $table->string('stored_at')->default('s3');
            $table->softDeletes();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
