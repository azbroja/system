<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class)->withPivot('selling_customer_price', 'purchase_customer_price', 'consumed_customer_price');
    }
    public function contact_people()
    {
        return $this->hasMany(ContactPerson::class);
    }

    public function customer_events()
    {
        return $this->hasMany(CustomerEvent::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function tests()
    {
        return $this->hasMany(Test::class);
    }

    public function rubbish()
    {
        return $this->hasMany(Rubbish::class);
    }

    public function gifts()
    {
        return $this->hasMany(Gift::class);
    }

    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    public function offers()
    {
        return $this->hasMany(Offer::class);
    }

    public function releases()
    {
        return $this->hasMany(Release::class);
    }

    public function complaints()
    {
        return $this->hasMany(Complaint::class);
    }

}
