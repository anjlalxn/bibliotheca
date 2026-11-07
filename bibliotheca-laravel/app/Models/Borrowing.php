<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Borrowing extends Model
{
    protected $primaryKey = 'borrowing_id';

    public $timestamps = false;

    protected $fillable = [
        'book_id',
        'member_id',
        'user_id',
        'borrowing_date',
        'due_date',
        'return_date',
        'status',
    ];

    protected $casts = [
        'borrowing_date' => 'date',
        'due_date'       => 'date',
        'return_date'    => 'date',
    ];

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class, 'member_id', 'member_id');
    }

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class, 'book_id', 'book_id');
    }

    // Shown in the table: worked out from the dates, same rule the dashboard uses
    public function getDisplayStatusAttribute(): string
    {
        if ($this->return_date) {
            return 'Returned';
        }

        if ($this->due_date && $this->due_date->lt(today())) {
            return 'Overdue';
        }

        return 'Borrowed';
    }
}