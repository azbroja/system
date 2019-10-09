<?php

namespace App;

use App\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{

    public function permissions()
    {
        return $this->belongsToMany(Permission::class);
    }

    public function customers()
    {
        return $this->hasMany(Customer::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function invoice_comments()
    {
        return $this->hasMany(InvoiceComment::class);
    }

    public function releases()
    {
        return $this->hasMany(Release::class);
    }

    public function complaints()
    {
        return $this->hasMany(Relase::class);
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

    public function work_hours()
    {
        return $this->hasMany(WorkHours::class);
    }

    public function customer_events()
    {
        return $this->hasMany(CustomerEvent::class);
    }

    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'surname', 'email', 'role_id', 'password',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */

}
