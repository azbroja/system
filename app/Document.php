<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
	public function customer()
	{
		return $this->belongsTo(Customer::class);
	}

    	public function offer()
	{
		return $this->hasOne(Offer::class);
	}
}
