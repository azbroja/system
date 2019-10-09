<?php

namespace App;

use DateInterval;
use Illuminate\Database\Eloquent\Model;

class WorkHours extends Model
{

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getWorkDurationAttribute(): DateInterval
    {
        return new DateInterval(sprintf('PT%dH%dM', floor($this->telephone_hours / 60), $this->telephone_hours % 60));
    }

}
