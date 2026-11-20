<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Old web addresses that should send visitors (and Google) to a new one.
        Schema::create('cms_redirects', function (Blueprint $table): void {
            $table->id();
            $table->string('from_path', 500)->unique();
            $table->string('to_path', 500);
            $table->unsignedSmallInteger('status_code')->default(301);
            $table->string('source', 20)->default('manual'); // manual | auto (created when an address changes)
            $table->unsignedInteger('hits')->default(0);
            $table->timestamp('last_hit_at')->nullable();
            $table->timestamps();
        });

        // Delivery record for the two emails each enquiry triggers.
        Schema::table('contact_messages', function (Blueprint $table): void {
            $table->timestamp('notification_sent_at')->nullable();
            $table->text('notification_error')->nullable();
            $table->timestamp('acknowledgement_sent_at')->nullable();
            $table->text('acknowledgement_error')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cms_redirects');
        Schema::table('contact_messages', function (Blueprint $table): void {
            $table->dropColumn(['notification_sent_at', 'notification_error', 'acknowledgement_sent_at', 'acknowledgement_error']);
        });
    }
};
