<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Book extends Model
{
    use HasFactory;

    // Статусы книг
    public const STATUS_AVAILABLE = 'available';
    public const STATUS_RESERVED = 'reserved';
    public const STATUS_UNAVAILABLE = 'unavailable';
    public const STATUS_ARCHIVED = 'archived';
    public const STATUS_LOST = 'lost';

    // Цвета статусов для UI
    public const STATUS_COLORS = [
        self::STATUS_AVAILABLE => 'bg-green-100 text-green-800',
        self::STATUS_RESERVED => 'bg-blue-100 text-blue-800',
        self::STATUS_UNAVAILABLE => 'bg-yellow-100 text-yellow-800',
        self::STATUS_ARCHIVED => 'bg-gray-100 text-gray-800',
        self::STATUS_LOST => 'bg-red-100 text-red-800',
    ];

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
        'status',
    ];

    protected static function booted()
    {
        static::saving(function ($book) {
            // Валидация количества копий
            $book->available_copies = max(0, min($book->available_copies, $book->total_copies));

            // Автоматическое обновление статуса только для обычных статусов
            if ($book->status !== self::STATUS_ARCHIVED &&
                $book->status !== self::STATUS_LOST &&
                $book->status !== self::STATUS_UNAVAILABLE) {

                $book->status = $book->available_copies > 0
                    ? self::STATUS_AVAILABLE
                    : self::STATUS_RESERVED;
            }
        });

        static::updated(function ($book) {
            // Если книга стала доступной, активируем ожидающие резервации
            if ($book->wasChanged('available_copies') && $book->available_copies > 0) {
                $book->activatePendingReservations();
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
        return $this->status === self::STATUS_AVAILABLE && $this->available_copies > 0;
    }

    // Получение доступных форматов
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

    // Методы для работы со статусами
    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            self::STATUS_AVAILABLE => 'Available',
            self::STATUS_RESERVED => 'Reserved',
            self::STATUS_UNAVAILABLE => 'Unavailable',
            self::STATUS_ARCHIVED => 'Archived',
            self::STATUS_LOST => 'Lost',
            default => 'Unknown',
        };
    }

    public function getStatusColorAttribute(): string
    {
        return self::STATUS_COLORS[$this->status] ?? 'bg-gray-100 text-gray-800';
    }

    // Бизнес-логика
    public function activatePendingReservations()
    {
        if ($this->available_copies <= 0) return;

        $pendingReservations = $this->reservations()
            ->where('status', Reservation::STATUS_PENDING)
            ->orderBy('reserved_at')
            ->get();

        foreach ($pendingReservations as $reservation) {
            if ($this->available_copies <= 0) break;

            if ($reservation->canBeActivated()) {
                $reservation->update([
                    'status' => Reservation::STATUS_ACTIVE,
                    'expires_at' => now()->addWeeks(2)
                ]);
                $this->decrement('available_copies');
            }
        }
    }

    public function markAsLost()
    {
        if ($this->status === self::STATUS_LOST) return false;

        $this->update([
            'status' => self::STATUS_LOST,
            'available_copies' => 0
        ]);

        return true;
    }

    public function archive()
    {
        if ($this->status === self::STATUS_ARCHIVED) return false;

        $this->update([
            'status' => self::STATUS_ARCHIVED,
            'available_copies' => 0
        ]);

        return true;
    }
}
