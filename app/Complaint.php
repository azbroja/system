<?php

namespace App;

use DB;
use Illuminate\Database\Eloquent\Model;

class Complaint extends Model
{

    protected $casts = [
        'pay_deadline' => 'date',
        'issued_at' => 'date',
        'sell_date' => 'date',
        'is_paid' => 'boolean',
        'total_value' => '`float`',
    ];

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
        return $this->belongsToMany(Product::class)->withPivot('quantity', 'net_unit_price', 'gross_unit_price');
    }

    public function document()
    {
        return $this->belongsTo(Document::class);
    }

    public function total_sum_gross()
    {
        return $this->products()->sum(DB::raw('gross_unit_price * quantity'));
    }

    public function total_sum()
    {
        return $this->products()->sum(DB::raw('net_unit_price'));
    }

    public function total_sum_gross_abs()
    {
        return $this->products()->abs($this->total_sum_gross());
    }

    public function total_sum_net()
    {
        return $this->products()->sum(DB::raw('net_unit_price * quantity'));
    }
}
