<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{

    public function customers()
    {
        return $this->belongsToMany(Customer::class)->withPivot('selling_customer_price', 'purchase_customer_price', 'consumed_customer_price', 'product_name');
    }

    public function invoices()
    {
        return $this->belongsToMany(Invoice::class);
    }
    public function offers()
    {
        return $this->belongsToMany(Offer::class);
    }

    public function orders()
    {
        return $this->belongsToMany(Order::class);
    }

    public function tests()
    {
        return $this->belongsToMany(Test::class);
    }

    public function gifts()
    {
        return $this->belongsToMany(Gift::class);
    }

    public function releases()
    {
        return $this->belongsToMany(Release::class);
    }

    public function complaints()
    {
        return $this->belongsToMany(Relase::class);
    }

}
