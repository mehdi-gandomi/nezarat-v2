<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\FormatesDates;
class InspectionLog extends Model
{
    use CrudTrait,FormatesDates;
    use HasFactory;
    protected $fillable=[
		"adapt",
		'user_id',
		'cctv_ip',
		'cctv_username',
		'cctv_port',
		'cctv_username',
        'unauthorized_users',
    "office_code",
	"inspection_period",
	"requires_second_inspection",
	"second_inspection_date",
	"inspector_signature",
	"inspection_date",
	"office_manager_signature",
	"legal_expert_signature",
	"second_inspection_summary",
	"obligations",
	"lat",
	"lng",
	'read_at',
	'inspection_type',
	'inspection_season',
    'recommendations_and_criticisms'
    ];
    public $casts=[
        'attachments'=>'array',
		'inspection_date'=>'date'
    ];
	public $dates=[
        'inspection_date','second_inspection_date'
    ];
    public function employees(){
        return $this->hasMany(InspectionLogEmployee::class,"inspection_log_id");
    }
	public function office(){
        return $this->belongsTo(OfficeFile::class,"office_code","office_code");
    }
    public function checklists(){
        return $this->hasMany(InspectionLogCheckList::class,"inspection_log_id");
    }
	public function user(){
		return $this->belongsTo(User::class);
	}
}
