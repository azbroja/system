<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomerEvent extends Model
{

    protected $casts = [
		'is_completed' => 'boolean',
	];


    public function customer()

    {
        return $this->belongsTo(Customer::class);

    }


    public function user()

    {
        return $this->belongsTo(User::class);

    }
    //poczytaj o tych relacjach w belong to meny musi byc pivot
//camelCase


}
