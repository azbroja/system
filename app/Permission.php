<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    
    public function users()

    {
        return $this->belongsToMany(User::class);
    }

    public function roles()

    {
        return $this->belongsToMany(Role::class);
    }

}
