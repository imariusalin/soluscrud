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
        Schema::create('servers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('location')->nullable();
            $table->text('description')->nullable();
            $table->integer('plan')->nullable();
            $table->json('ssh_keys')->nullable();
            $table->string('password');
            $table->integer('user');
            $table->integer('project')->nullable();
            $table->json('backup_settings')->nullable();
            $table->json('ip_types')->nullable();
            $table->integer('additional_ip_count')->nullable();
            $table->integer('additional_ipv6_count')->nullable();
            $table->json('additional_disks')->nullable();
            $table->integer('os');
            $table->integer('compute_resource')->nullable();
            $table->string('primary_ip');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('servers');
    }
};
