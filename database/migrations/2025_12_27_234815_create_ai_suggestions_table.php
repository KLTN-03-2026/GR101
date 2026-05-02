<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ai_suggestions', function (Blueprint $table) {
            $table->id();
            $table->string('suggestion_type')->comment('pricing, inventory, combo, trending');
            $table->unsignedBigInteger('product_id')->nullable();
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->string('title');
            $table->text('description');
            $table->text('action_recommendation')->nullable();
            $table->json('metadata')->nullable()->comment('Additional data like metrics, related products');
            $table->integer('priority')->default(1)->comment('1=low, 2=medium, 3=high');
            $table->boolean('is_active')->default(true);
            $table->boolean('is_dismissed')->default(false);
            $table->timestamp('dismissed_at')->nullable();
            $table->timestamps();

            $table->index(['suggestion_type', 'is_active']);
            $table->index(['priority', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ai_suggestions');
    }
};
