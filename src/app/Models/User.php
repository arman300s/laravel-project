<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = ['name', 'email', 'password', 'role_id'];


    public function role(){
        return $this->belongsTo('App\Models\Role');
    }
    public function transactions(){
        return $this->hasMany('App\Models\Transaction');
    }

    protected static function boot(){
        parent::boot();
        static::creating(function($user){
            if(!$user->role_id){
                $user->role_id=1;
            }
        });
    }
}
