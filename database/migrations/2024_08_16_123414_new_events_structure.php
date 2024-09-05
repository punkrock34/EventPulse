<?php

use App\Constants\DatabaseTables;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private $eventsTableName = DatabaseTables::EVENTS->value;

    private $usersTableName = DatabaseTables::USERS->value;

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table($this->eventsTableName, function (Blueprint $table) {
            $table->timestamp('start_date');
            $table->timestamp('end_date');

            $table->timestamp('completed_at')->nullable();

            $table->dropForeign(['owner_id']);
            $table->dropColumn('owner_id');

            $table->bigInteger('owner_id')->unsigned()->after('id');
            $table->foreign('owner_id')->references('id')->on($this->usersTableName)->onDelete('cascade');

            $table->text('notes')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table($this->eventsTableName, function (Blueprint $table) {
            $table->dropColumn('start_date');
            $table->dropColumn('end_date');
            $table->dropColumn('completed_at');
            $table->dropColumn('notes');
            $table->dropForeign(['owner_id']);
            $table->dropColumn('owner_id');
        });
    }
};
