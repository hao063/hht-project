<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SessionRegistration extends Model
{

    use HasFactory;

    protected $table = 'campaign_tickets';

    public $timestamps = false;

    protected $fillable = ['registration_id', 'session_id'];

}
