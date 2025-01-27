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
        Schema::table('leaves', function (Blueprint $table) {
            $table->foreignId('leave_type_id')->after('to')->constrained()->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreignId('processing_officer_role')->nullable()->after('leave_status')->constrained('roles')->onUpdate('CASCADE')->onDelete('CASCADE');
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
                $table->dropConstrainedForeignId('leave_type_id');
                $table->dropConstrainedForeignId('processing_officer_role');
            });
    }
};
