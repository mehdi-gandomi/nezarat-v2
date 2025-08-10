<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{

/**
* @var  string
*/
protected $table = 'cities';

protected $casts = [
];

public function province()
{
return $this->belongsTo(Province::class, 'province_id', 'id');
}
}
