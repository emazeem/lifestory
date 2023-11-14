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
        Schema::create('sessions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('time_slot_id')->nullable();
            $table->string('uuid');
            $table->string('meeting_id');
            $table->string('host_email');
            $table->string('topic');
            $table->dateTime('start_time')->nullable();
            $table->dateTime('end_time')->nullable();
            $table->integer('duration');
            $table->text('start_url');
            $table->string('join_url');
            $table->boolean("reminder")->default(0);
            $table->string('password');
            $table->boolean('payment')->default(0);
            $table->softDeletes();
            $table->string('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sessions');
    }
};
