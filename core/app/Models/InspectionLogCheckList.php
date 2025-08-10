<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InspectionLogCheckList extends Model
{
    use HasFactory;
    protected $fillable=[
        'inspection_log_id',
        'question_category_id',
        'adapt_percent'
    ];
    public function questions(){
        return $this->hasMany(InspectionLogCheckListQuestions::class,"check_list_question_id");
    }
    public function category(){
        return $this->belongsTo(GeneralQuestionCategory::class,"question_category_id");
    }
}
