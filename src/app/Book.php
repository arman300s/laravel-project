<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
