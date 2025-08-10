<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GeneralQuestion extends Model
{
    use HasFactory;
	public function category(){
		return $this->belongsTo(GeneralQuestionCategory::class);
	}
}
