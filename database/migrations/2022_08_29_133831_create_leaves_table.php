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
        Schema::create('leaves', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->date('from');
            $table->date('to');
            $table->boolean('use_confessionnels')->default(false);
            $table->boolean('travelling')->default(false);
            $table->string('attachment_path')->nullable();
            $table->date('date_of_submission')->default(now()->format('Y/m/d'));
            $table->integer('leave_status')->default(0);
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
        Schema::table('leaves', function (Blueprint $table) {
            $table->dropConstrainedForeignId('employee_id');
        });
        Schema::dropIfExists('leaves');
    }
};
