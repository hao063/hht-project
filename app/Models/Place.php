<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Place extends Model
{

    use HasFactory;

    protected $table = 'places';

    public $timestamps = false;

    protected $fillable = ['area_id', 'name', 'capacity'];

    public function Sessions()
    {
        return $this->hasMany(Session::class);
    }

    public function Area()
    {
        return $this->belongsTo(Area::class);
    }

}
