<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IssueMedia extends Model
{
    public function issue()
    {
        return $this->belongsTo(Issue::class);
    }
}
