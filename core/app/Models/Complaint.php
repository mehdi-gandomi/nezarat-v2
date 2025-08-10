<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;

class Complaint extends Model
{
    use CrudTrait;
protected $fillable=[
	'first_name',
	'last_name',
	'mobile',
	'national_code',
	'birth_date',
	'office_code',
	'hide_my_name',
	'subject',
	'message'
];
/**
* @var  string
*/
protected $table = 'complaints';

protected $casts = [
];


public function getCreatedAtFaAttribute(){
    return verta($this->created_at)->formatJalaliDateTime();
}
public function getBirthDateFaAttribute(){
    return verta($this->birth_date)->formatJalaliDate();
}

}
