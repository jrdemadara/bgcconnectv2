<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    protected $connection = "pgsql";

    public function up()
    {
        // Chats table
        Schema::create("chats", function (Blueprint $table) {
            $table->id();
            $table->enum("chat_type", ["direct", "group", "topic"]);
            $table->string("name")->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // Chat Participants
        Schema::create("chat_participants", function (Blueprint $table) {
            $table->id();
            $table->foreignId("chat_id")->constrained()->onDelete("cascade");
            $table->integer("user_id");
            $table->enum("role", ["member", "admin"])->nullable();
            $table->timestamp("joined_at")->useCurrent();
            $table->boolean("is_joined")->default(false);
            $table->unique(["chat_id", "user_id"]);
            $table->timestamps();
            $table->softDeletes();
            // Add index to speed up lookups
            $table->index(["chat_id", "user_id"]);
        });

        // Messages
        Schema::create("messages", function (Blueprint $table) {
            $table->id();
            $table->integer("sender_id");
            $table->foreignId("chat_id")->constrained()->onDelete("cascade");
            $table->text("content");
            $table->enum("message_type", ["text", "image", "video", "file"])->default("text");
            $table->foreignId("reply_to")->nullable()->constrained("messages")->nullOnDelete();
            $table->index(["chat_id", "sender_id"]);
            $table->index("reply_to");
            $table->timestamp("edited_at")->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // Message Status
        Schema::create("message_status", function (Blueprint $table) {
            $table->id();
            $table->foreignId("message_id")->constrained()->onDelete("cascade");
            $table->integer("user_id");
            $table->enum("status", ["sent", "delivered", "read"])->default("sent");
            $table->timestamp("updated_at")->useCurrent()->useCurrentOnUpdate();
            $table->unique(["message_id", "user_id"]);
            $table->index(["message_id", "user_id"]);
            $table->softDeletes();
        });

        Schema::create("message_requests", function (Blueprint $table) {
            $table->id();
            $table->integer("sender_id");
            $table->integer("recipient_id");
            $table->enum("status", ["pending", "accepted", "declined"])->default("pending");
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists("message_requests");
        Schema::dropIfExists("message_status");
        Schema::dropIfExists("messages");
        Schema::dropIfExists("chat_participants");
        Schema::dropIfExists("chats");
    }
};
