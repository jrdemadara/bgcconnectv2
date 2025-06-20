<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    protected $connection = "pgsql";

    public function up(): void
    {
        Schema::create("device_sync", function (Blueprint $table) {
            $table->id();
            $table->string("type");
            $table->boolean("is_sync");
            $table->integer("data_id");
            $table->integer("device_id");
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("device_sync");
    }
};
