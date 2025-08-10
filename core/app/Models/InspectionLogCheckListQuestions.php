<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InspectionLogCheckListQuestions extends Model
{
    use HasFactory;
    protected $fillable=[
        'general_question_id',
        'check_list_question_id',
        'description',
        'rating'
    ];
	public $table="inspection_log_check_list_questions";
    public function question(){
        return $this->belongsTo(GeneralQuestion::class,"general_question_id");
    }
    public function getComputedRatingAttribute(){
        $ratings=[
            1=>"بله",
            0=>"خیر",
            
        ];
        return $this->rating &&  $ratings[$this->rating] ? $ratings[$this->rating]:"";
    }
}
