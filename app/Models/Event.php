<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Event extends Model
{
    protected $fillable = [
        'name',
        'description',
        'date',
        'start_time',
        'end_time',
        'image',
        'user_id',
        'location',
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function ticketTypes()
    {
        return $this->hasMany(TicketType::class);
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }


    public function getTicketStatusAttribute()
    {
        $now = Carbon::now();
        $startTime = Carbon::parse($this->start_time); // Assuming `start_time` is a datetime field in your table.

        return $now->lessThan($startTime) ? 'Open' : 'Closed';
    }

}
