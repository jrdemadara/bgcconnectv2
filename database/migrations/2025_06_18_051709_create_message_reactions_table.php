<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    protected $connection = "pgsql";

    public function up(): void
    {
        Schema::create("message_reactions", function (Blueprint $table) {
            $table->id();
            $table->foreignId("message_id")->constrained()->onDelete("cascade");
            $table->integer("user_id");
            $table->string("reaction");
            $table->timestamp("updated_at")->useCurrent()->useCurrentOnUpdate();
            $table->unique(["message_id", "user_id"]);
            $table->index(["message_id", "user_id"]);
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("message_reactions");
    }
};
