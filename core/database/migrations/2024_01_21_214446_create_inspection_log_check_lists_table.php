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
        Schema::create('inspection_log_check_lists', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("general_question_id");
            $table->text("description");
            $table->tinyInteger("rating");//0 -> ضعیف
            //1->متوسط
            //2->خوب
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inspection_log_check_lists');
    }
};
