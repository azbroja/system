<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\Pivot;

class InvoiceProduct extends Pivot
{

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function product()
    {
        return $this->belongsTo('App\Product');
    }

    public function customer()
    {
        return $this->belongsTo('App\Customer');
    }

}
