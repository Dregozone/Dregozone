<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Add secret_key, emails_sent, and rename is_active to is_subscribed
     * on the newsletter_subscribers table.
     */
    public function up(): void
    {
        Schema::table('newsletter_subscribers', function (Blueprint $table) {
            $table->renameColumn('is_active', 'is_subscribed');
            $table->string('secret_key', 64)->unique()->after('unsubscribed_at');
            $table->unsignedInteger('emails_sent')->default(0)->after('secret_key');
        });
    }

    public function down(): void
    {
        Schema::table('newsletter_subscribers', function (Blueprint $table) {
            $table->renameColumn('is_subscribed', 'is_active');
            $table->dropUnique(['secret_key']);
            $table->dropColumn(['secret_key', 'emails_sent']);
        });
    }
};
