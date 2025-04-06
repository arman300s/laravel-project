<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Borrowing extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'book_id',
        'borrowed_at',
        'returned_at',
        'due_at',
        'status',
        'description',
    ];

    protected $casts = [
        'borrowed_at' => 'datetime',
        'returned_at' => 'datetime',
        'due_at' => 'datetime',
    ];

    public const STATUS_PENDING = 'pending';
    public const STATUS_ACTIVE = 'active';
    public const STATUS_RETURNED = 'returned';
    public const STATUS_OVERDUE = 'overdue';

    protected static function booted()
    {
        static::creating(function ($borrowing) {
            // При создании бронирования устанавливаем borrowed_at, если не установлен
            if (empty($borrowing->borrowed_at)) {
                $borrowing->borrowed_at = now();
            }
        });

        static::updating(function ($borrowing) {
            // При изменении статуса на returned устанавливаем returned_at, если не установлен
            if ($borrowing->isDirty('status') &&
                $borrowing->status === self::STATUS_RETURNED &&
                empty($borrowing->returned_at)) {
                $borrowing->returned_at = now();
            }
        });
    }

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            self::STATUS_PENDING => 'Pending',
            self::STATUS_ACTIVE => 'Active',
            self::STATUS_RETURNED => 'Returned',
            self::STATUS_OVERDUE => 'Overdue',
            default => 'Unknown',
        };
    }
}
