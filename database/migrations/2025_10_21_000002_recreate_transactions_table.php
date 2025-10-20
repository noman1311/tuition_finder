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
        // Drop the existing transactions table if it exists
        Schema::dropIfExists('transactions');
        
        // Create the new transactions table with simplified wallet feature (no approval system)
        Schema::create('transactions', function (Blueprint $table) {
            $table->id('transaction_id');
            $table->unsignedBigInteger('user_id');
            $table->string('account_number', 50)->nullable()->comment('Payment account number - NULL for non-wallet transactions');
            $table->decimal('amount', 10, 2);
            $table->enum('type', ['bkash', 'rocket', 'banking', 'nagad']);
            $table->text('description')->nullable();
            $table->date('transaction_date');
            $table->timestamps();
            
            // Foreign key constraint
            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');
            
            // Indexes for better performance
            $table->index('user_id', 'idx_user_id');
            $table->index('account_number', 'idx_account_number');
            $table->index('created_at', 'idx_created_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions');
    }
};