<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

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
            // Add as nullable first so SQLite can add the column to an existing table.
            $table->string('secret_key', 64)->nullable()->unique()->after('unsubscribed_at');
            $table->unsignedInteger('emails_sent')->default(0)->after('secret_key');
        });

        // Backfill any existing rows that don't yet have a secret_key.
        DB::table('newsletter_subscribers')
            ->whereNull('secret_key')
            ->orderBy('id')
            ->each(function (object $row): void {
                DB::table('newsletter_subscribers')
                    ->where('id', $row->id)
                    ->update(['secret_key' => Str::random(64)]);
            });

        // Make NOT NULL on databases that support it (MySQL/PostgreSQL).
        // SQLite does not support modifying column constraints, so we skip it there;
        // the model's booted() method ensures every new row always receives a value.
        if (DB::getDriverName() !== 'sqlite') {
            Schema::table('newsletter_subscribers', function (Blueprint $table) {
                $table->string('secret_key', 64)->nullable(false)->change();
            });
        }
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
