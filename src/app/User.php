<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    public function role(){
        return $this->belongsTo('App\Role');
    }
    public function transactions(){
        return $this->hasMany('App\Transaction');
    }
}
