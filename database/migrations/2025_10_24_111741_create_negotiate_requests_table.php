<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('negotiate_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('buyer_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('seller_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->decimal('proposed_price', 10, 2);
            $table->text('message')->nullable();
            $table->enum('status', ['pending', 'accepted', 'counter_offered', 'declined'])->default('pending');
            $table->foreignId('parent_request_id')->nullable()->constrained('negotiate_requests')->onDelete('cascade');
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
            
            $table->index(['buyer_id', 'seller_id', 'product_id']);
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('negotiate_requests');
    }
};
