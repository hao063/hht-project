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

    public function Places(){
        return $this->hasManyThrough(
            Place::class,
            Area::class,
            'campaign_id',
            'area_id',
            'id',
            'id'
        );
    }

    public function getListSessions()
    {
        $sessions = [];
        foreach($this->Places as $place){
            foreach($place->Sessions as $session_item){
                $sessions[] = $session_item;
            }
        }
        return $sessions;
    }
}
