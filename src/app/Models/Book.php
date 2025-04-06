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

    protected static function booted()
    {
        static::saving(function ($book) {
            // Гарантируем, что available_copies не превышает total_copies
            if ($book->available_copies > $book->total_copies) {
                $book->available_copies = $book->total_copies;
            }
            // Гарантируем, что available_copies не отрицательное
            if ($book->available_copies < 0) {
                $book->available_copies = 0;
            }
        });
    }

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
        return $this->available_copies > 0;
    }

    public function getAvailableFormats()
    {
        $formats = [];
        if ($this->file_pdf) {
            $formats['pdf'] = asset('storage/' . $this->file_pdf);
        }
        if ($this->file_docx) {
            $formats['docx'] = asset('storage/' . $this->file_docx);
        }
        if ($this->file_epub) {
            $formats['epub'] = asset('storage/' . $this->file_epub);
        }
        return $formats;
    }
}
