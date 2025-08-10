<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Province extends Model
{

/**
* @var  string
*/
protected $table = 'provinces';

protected $casts = [
];

public function cities()
{
return $this->hasMany(City::class, 'province_id', 'id');
}
}
