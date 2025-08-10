<?php
namespace App\Traits;

use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Verta;
trait FormatesDates{
    public function getAttribute($key)
    {
        if ($key && preg_match('/^([A-Za-z0-9_]+)(_fa|_en)(|_f|_ft|_ftt)$/', $key, $matches)) {
            if ($this->isDateAttribute($matches[1]) || (isset($this->dates) && in_array($matches[1],$this->dates))) {
                if(!$this->attributes[$matches[1]]) return parent::getAttribute($key);
                if ($matches[2] == '_fa') {
                    $date=parent::getAttribute($matches[1]);
                    $attributeValue = Verta::instance($date)->timezone(config("app.timezone"));
                    if(isset(self::$dateFormats[$matches[1]])){
                        return $attributeValue->format(self::$dateFormats[$matches[1]]);
                    }
                    return $attributeValue->formatJalaliDate();
                }else if ($matches[2] == '_en') {
                    $date=parent::getAttribute($matches[1]);
                    $attributeValue=Carbon::parse($date)->setTimezone(config("app.timezone"));
                    if(isset(self::$dateFormats[$matches[1]])){
                        return $attributeValue->format(self::$dateFormats[$matches[1]]);
                    }
                    return $attributeValue->format("Y-m-d H:i");
                }

                return parent::getAttribute($key);
            }
        }
        if ($this->isDateAttribute($key)) {
            $date=parent::getAttribute($key);
            if(!$date) return $date;
            $attributeValue=Carbon::parse($date)->setTimezone(config("app.timezone"));
            return $attributeValue;
        }
        return parent::getAttribute($key);
    }
    public function attributesToArray()
    {
        foreach($this->getDates() as $date){
            if(isset($this->attributes[$date]) && $this->attributes[$date]){
                $this->setAttribute($date."_fa",$this->getAttribute($date."_fa"));
                $this->setAttribute($date."_en",$this->getAttribute($date."_en"));
            }
        }
        $data= parent::attributesToArray();
        // if($this->translatable){
        //     foreach ($this->translatable as $field)
		// 	    $data[$field] = $this->{$field};
        // }
        return $data;
    }
}
