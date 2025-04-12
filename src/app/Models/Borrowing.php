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
        'from_reservation_id',
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
        static::saving(function ($model) {
            if (isset($model->book)) {
                if ($model->book->available_copies > $model->book->total_copies) {
                    $model->book->available_copies = $model->book->total_copies;
                }
                if ($model->book->available_copies < 0) {
                    $model->book->available_copies = 0;
                }
            }

            if ($model->isDirty() && !$model->exists && empty($model->borrowed_at)) {
                $model->borrowed_at = now();
            }
            $model->checkAndUpdateOverdueStatus();
        });

        static::creating(function ($model) {
            if (empty($model->borrowed_at)) {
                $model->borrowed_at = now();
            }
        });

        static::updating(function ($model) {
            if ($model->isDirty('status') &&
                $model->status === self::STATUS_RETURNED &&
                empty($model->returned_at)) {
                $model->returned_at = now();
            }
        });

        static::retrieved(function ($model) {
            $model->checkAndUpdateOverdueStatus();
        });
    }
    public function checkAndUpdateOverdueStatus(): bool
    {
        if ($this->status === self::STATUS_ACTIVE &&
            $this->due_at->isPast() &&
            is_null($this->returned_at)) {

            $this->status = self::STATUS_OVERDUE;
            $this->saveQuietly();
            return true;
        }
        return false;
    }
    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function reservation()
    {
        return $this->belongsTo(Reservation::class, 'from_reservation_id');
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
    public function isOverdue(): bool
    {
        return $this->status === self::STATUS_OVERDUE ||
            ($this->status === self::STATUS_ACTIVE && $this->due_at->isPast());
    }
}
