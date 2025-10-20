<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // First, let's modify the existing status column to have the correct ENUM values
        DB::statement("ALTER TABLE applications MODIFY COLUMN status ENUM('pending', 'accepted', 'rejected', 'withdrawn') NOT NULL DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Revert to original status column if needed
        DB::statement("ALTER TABLE applications MODIFY COLUMN status VARCHAR(255) NOT NULL DEFAULT 'pending'");
    }
};