<?php

use App\Constants\DatabaseTables;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private $eventParticipantsTableName = DatabaseTables::EVENT_PARTICIPANTS->value;

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create($this->eventParticipantsTableName, function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists($this->eventParticipantsTableName);
    }
};
