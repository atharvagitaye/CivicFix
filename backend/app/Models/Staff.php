<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    public function issues()
    {
        return $this->hasMany(Issue::class);
    }
}
