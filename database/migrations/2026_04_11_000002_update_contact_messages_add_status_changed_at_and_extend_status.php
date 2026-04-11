<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Add status_changed_at and extend status enum on the contact_messages table.
     */
    public function up(): void
    {
        Schema::table('contact_messages', function (Blueprint $table) {
            $table->enum('status', ['new', 'read', 'replied', 'ignored', 'actioned', 'archived'])
                ->default('new')
                ->change();
            $table->timestamp('status_changed_at')->nullable()->after('metadata');
        });
    }

    public function down(): void
    {
        Schema::table('contact_messages', function (Blueprint $table) {
            $table->enum('status', ['new', 'read', 'replied', 'archived'])
                ->default('new')
                ->change();
            $table->dropColumn('status_changed_at');
        });
    }
};
