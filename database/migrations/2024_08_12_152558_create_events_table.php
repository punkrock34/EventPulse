<?php

use App\Constants\DatabaseTables;
use App\Constants\EventStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private $eventsTableName = DatabaseTables::EVENTS->value;

    private $usersTableName = DatabaseTables::USERS->value;

    public function up(): void
    {
        if (Schema::hasTable($this->eventsTableName)) {
            Log::info('Events table already exists. Skipping...');

            return;
        }

        Schema::create($this->eventsTableName, function (Blueprint $table) {
            $table->id();
            $table->string('owner_id');
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('status', EventStatus::values())->default(EventStatus::UPCOMING->value);
            $table->timestamps();

            $table->foreign('owner_id')->references('id')->on($this->usersTableName);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists($this->eventsTableName);
    }
};
