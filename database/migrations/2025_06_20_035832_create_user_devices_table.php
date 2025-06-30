<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    protected $connection = "mysql";
    public function up(): void
    {
        Schema::create("user_devices", function (Blueprint $table) {
            $table->id();
            $table->string("device_id")->unique();
            $table->string("name");
            $table->timestamp("last_login")->useCurrent();
            $table->decimal("login_lat", 10, 8);
            $table->decimal("login_lon", 11, 8);
            $table->boolean("is_online");
            $table->foreignId("user_id")->constrained("users")->onDelete("cascade");
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("user_devices");
    }
};
