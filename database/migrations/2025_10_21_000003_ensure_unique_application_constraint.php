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
        Schema::table('applications', function (Blueprint $table) {
            // Drop the constraint if it exists and recreate it to ensure it's properly set
            try {
                $table->dropUnique(['offer_id', 'teacher_id']);
            } catch (\Exception $e) {
                // Constraint might not exist, continue
            }
            
            // Add the unique constraint to prevent duplicate applications
            $table->unique(['offer_id', 'teacher_id'], 'unique_application');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->dropUnique('unique_application');
        });
    }
};