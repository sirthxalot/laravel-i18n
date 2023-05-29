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
            ->create(config('i18n.database.tables.translations'), function (Blueprint $table) {
                $table->increments('id');
                $table->string('locale', 6);
                $table->foreign('locale')
                    ->references('locale')
                    ->on(config('i18n.database.tables.languages'));
                $table->text('key');
                $table->text('message')->nullable();
                $table->timestamps();
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection(config('i18n.database.connection'))
            ->dropIfExists(config('i18n.database.tables.translations'));
    }
};
