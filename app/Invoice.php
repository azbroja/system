<?php

namespace App;

use DB;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class Invoice extends Model
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
        return $this->belongsToMany(Product::class)->withPivot('quantity', 'product_name', 'net_unit_price', 'gross_unit_price', 'purchase_price');

    }
    public function invoice_comments()
    {
        return $this->hasMany(InvoiceComment::class);
    }

    public function comments()
    {
        return InvoiceComment::where('invoice_id', '=', $this->id)->get();
    }

    public function document()
    {
        return $this->belongsTo(Document::class);

    }

    public function total_sum_gross()
    {

        return $this->products()->sum(DB::raw('gross_unit_price * quantity'));

    }
    public function total_sum_gross_abs()
    {

        return $this->products()->abs($this->total_sum_gross());

    }

    public function total_net_sum()
    {

        return $this->products()->sum(DB::raw('net_unit_price * quantity'));

    }

    public function total_sum_net()
    {

        return $this->products()->sum(DB::raw('net_unit_price * quantity'));

    }

    public function invoicesCount()
    {
        return $this->products()->sum(DB::raw('net_unit_price * quantity'));
    }

    public function scopeChartStats(Builder $builder, string $valueColumn, string $dateColumn, ?string $from = null, ?string $to = null)
    {
        $query = clone $builder;

        if ($from !== null) {
            $stats = $query->whereDate($dateColumn, '>=', $from);
        }

        if ($to !== null) {
            $stats = $query->whereDate($dateColumn, '<=', $to);
        }

        $stats = $query->groupBy('date')
            ->get([
                DB::raw("date($dateColumn) as date"),
                DB::raw("sum($valueColumn) as sum"),
            ])
            ->pluck('sum', 'date');

        $fromTime = strtotime($from ?? $stats->keys()->min());
        $toTime = strtotime($to ?? $stats->keys()->max());

        $dates = Collection::times(
            round(($toTime - $fromTime) / 86400) + 1,
            function (int $dayIndex) use ($fromTime): string {
                return date('Y-m-d', $fromTime + ($dayIndex - 1) * 86400);
            }
        );

        $totalSum = 0.0;

        return $dates->combine(collect()->pad($dates->count(), $totalSum))
            ->merge($stats)
            ->map(function (float $sum) use (&$totalSum): float {
                return $totalSum += $sum;
            });
    }

}
