<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{

    use HasFactory;

    protected $table = 'campaigns';

    public $timestamps = false;

    protected $fillable = ['organizer_id', 'name', 'slug', 'date'];

    public function Areas()
    {
        return $this->hasMany(Area::class);
    }

    public function Tickets()
    {
        return $this->hasMany(CampaignTicket::class);
    }

}
