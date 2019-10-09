<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    public function customer()
    {
        return $this->belongsTo(Customer::class);

    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function newNumber(int $year = null): int
    {
        if ($year === null) {
            $year = date('Y');
        }

        return $this->whereYear('created_at', $year)->orderByDesc('created_at')->value('invoice_number') + 1;
    }

    public function products()
    {
        return $this->belongsToMany(Product::class)->withPivot('selling_customer_price', 'purchase_customer_price', 'consumed_customer_price', 'product_name');

    }

    public function document()
    {
        return $this->belongsTo(Document::class);

    }

}
