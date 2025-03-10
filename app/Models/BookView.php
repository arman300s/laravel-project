<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookView extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'book_id'];

    // Отношение: один просмотр принадлежит одному пользователю
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Отношение: один просмотр связан с одной книгой
    public function book()
    {
        return $this->belongsTo(Book::class);
    }
}
