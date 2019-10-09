<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InvoiceComment extends Model
{
    public function invoices()

    {
        return $this->belongsToMany(Invoice::class);

    }

    public function users()

    {
        return $this->belongsToMany(User::class);

    }
}
