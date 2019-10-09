<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ContactPerson extends Model
{
    public function customer()

    {
        return $this->belongsTo(Customer::class);

    }
}
