<?php

namespace App\Models;

use Axiostudio\Comuni\Models\Zip;
use Axiostudio\Comuni\Models\City;
use Axiostudio\Comuni\Models\Province;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'surname',
        'company',
        'address',
        'province_id',
        'city_id',
        'zip_id'
    ];

    public function province()
    {
        return $this->belongsTo(Province::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function zip()
    {
        return $this->belongsTo(Zip::class);
    }

}
