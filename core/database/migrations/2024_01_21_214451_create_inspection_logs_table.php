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
        Schema::create('inspection_logs', function (Blueprint $table) {
            $table->id();
            $table->string("office_code",50);
            $table->string("inspection_period");
            $table->boolean("requires_second_inspection")->default(0);//ایا نیاز به بازرسی مجدد دارد؟
            $table->date("second_inspection_date")->nullable();
            $table->string("inspector_signature");
            $table->date("inspection_date");
            $table->string("office_manager_signature");
            $table->string("legal_expert_signature");//امضا کارشناس حقوقی دفترنماینده مدیر دفتر
            $table->text("second_inspection_summary")->nullable();//جمع بندی و ارزیابی حاصل از ارزیابی مجدد
            $table->text("obligations")->nullable();//صورتجلسه و تعهدات مدیر دفتر / کارشناس حقوقی
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inspection_logs');
    }
};
