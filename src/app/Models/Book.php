<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'isbn',
        'description',
        'published_year',
        'author_id',
        'category_id',
        'available_copies',
        'total_copies',
        'file_pdf',
        'file_docx',
        'file_epub',
    ];

    public function author()
    {
        return $this->belongsTo(Author::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function borrowings()
    {
        return $this->hasMany(Borrowing::class);
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    public function isAvailable(): bool
    {
        return $this->available_copies > 0 && $this->reservations()->where('status', 'pending')->count() == 0;
    }

    public function isReservable(): bool
    {
        return $this->available_copies > 0 && $this->reservations()->where('status', 'pending')->count() == 0 && $this->borrowings()->where('status', 'active')->count() == 0;
    }

    public function getAvailableFormats()
    {
        $formats = [];
        if ($this->file_pdf) {
            $formats['pdf'] = asset('storage/books/' . $this->file_pdf);
        }
        if ($this->file_docx) {
            $formats['docx'] = asset('storage/books/' . $this->file_docx);
        }
        if ($this->file_epub) {
            $formats['epub'] = asset('storage/books/' . $this->file_epub);
        }
        return $formats;
    }
}
