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
        Schema::connection(config('i18n.database.connection'))
            ->create(config('i18n.database.tables.languages'), function (Blueprint $table) {
                $table->increments('id');
                $table->string('locale', 6)->unique();
                $table->timestamps();
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection(config('i18n.database.connection'))
            ->dropIfExists(config('i18n.database.tables.languages'));
    }
};
