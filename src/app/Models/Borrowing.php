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
        static::saving(function ($model) {
            // Проверка доступных копий книги
            if (isset($model->book)) {
                if ($model->book->available_copies > $model->book->total_copies) {
                    $model->book->available_copies = $model->book->total_copies;
                }
                if ($model->book->available_copies < 0) {
                    $model->book->available_copies = 0;
                }
            }

            // Автоматическая установка borrowed_at при создании
            if ($model->isDirty() && !$model->exists && empty($model->borrowed_at)) {
                $model->borrowed_at = now();
            }

            // Автоматическая проверка просрочки
            $model->checkAndUpdateOverdueStatus();
        });

        static::creating(function ($model) {
            if (empty($model->borrowed_at)) {
                $model->borrowed_at = now();
            }
        });

        static::updating(function ($model) {
            // Автоматическая установка returned_at при изменении статуса на "returned"
            if ($model->isDirty('status') &&
                $model->status === self::STATUS_RETURNED &&
                empty($model->returned_at)) {
                $model->returned_at = now();
            }
        });

        static::retrieved(function ($model) {
            // Проверка просрочки при загрузке модели
            $model->checkAndUpdateOverdueStatus();
        });
    }

    /**
     * Проверяет и обновляет статус просрочки
     */
    public function checkAndUpdateOverdueStatus(): bool
    {
        if ($this->status === self::STATUS_ACTIVE &&
            $this->due_at->isPast() &&
            is_null($this->returned_at)) {

            $this->status = self::STATUS_OVERDUE;
            $this->saveQuietly(); // Сохраняем без повторного вызова событий
            return true;
        }
        return false;
    }

    /**
     * Отношение к книге
     */
    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    /**
     * Отношение к пользователю
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Возвращает читабельное название статуса
     */
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

    /**
     * Проверяет, просрочен ли займ
     */
    public function isOverdue(): bool
    {
        return $this->status === self::STATUS_OVERDUE ||
            ($this->status === self::STATUS_ACTIVE && $this->due_at->isPast());
    }
}
