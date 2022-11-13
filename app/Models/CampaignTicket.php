<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CampaignTicket extends Model
{

    use HasFactory;

    protected $table = 'campaign_tickets';

    public $timestamps = false;

    protected $fillable = ['campaign_id', 'name', 'cost', 'special_validity'];

}
