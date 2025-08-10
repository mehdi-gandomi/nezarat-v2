<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InspectionLogEmployee extends Model
{
    use CrudTrait;
    use HasFactory;
    protected $fillable=[
        'inspection_log_id',
        'national_code',
        'presency',
        'presency_description',
        'knowledge',
        'knowledge_description',
        'office_grooming',
        'office_grooming_description',
        'cooperation',
        'cooperation_description',
        'satisfaction',
        'satisfaction_description'
    ];
}
