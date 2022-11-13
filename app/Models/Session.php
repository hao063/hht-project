<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Session extends Model
{

    use HasFactory;

    protected $table = 'sessions';

    public $timestamps = false;

    protected $fillable = ['place_id', 'title', 'description', 'vaccinate', 'start', 'end', 'type', 'cost'];

    public function Place()
    {
        return $this->belongsTo(Place::class);
    }

}
