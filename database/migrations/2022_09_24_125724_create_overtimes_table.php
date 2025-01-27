<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('overtimes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->date('date');
            $table->time('from');
            $table->time('to');
            $table->string('hours');
            $table->string('objective')->nullable();
            $table->date('date_of_submission')->default(now()->format('Y/m/d'));
            $table->integer('overtime_status')->default(0);
            $table->foreignId('processing_officer_role')->nullable()->constrained('roles')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->string('cancellation_reason')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('overtimes', function (Blueprint $table) {
            $table->dropConstrainedForeignId('employee_id');
            $table->dropConstrainedForeignId('processing_officer_role');
        });
        Schema::dropIfExists('overtimes');
    }
};
