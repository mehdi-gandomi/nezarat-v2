<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OfficeFile extends Model
{
    use CrudTrait;
    use HasFactory;
    protected $guarded=[];
    public function getPersonelImageUrlAttribute(){
        return asset("/storage/".$this->personel_image);
    }
    public function getComputedGenderAttribute(){
        return $this->gender == 1 ? "مرد":"زن";
    }
    public function inspectionLogs($crud=false){
        return '<a class="btn btn-sm btn-link" href="'.route('inspection-log.index',['office_code'=>$this->office_code]).'" data-toggle="tooltip" title=""><i class="la la-list"></i> لیست سوابق بازرسی</a>';
    }
	public function province(){
		return $this->belongsTo(Province::class);
	}
		public function city(){
		return $this->belongsTo(City::class);
	}
			public function user(){
		return $this->belongsTo(User::class);
	}
}
