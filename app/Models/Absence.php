<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Absence extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'comment',
        'startHour',
        'endHour',
        'hourNumber',
        'dayNumber',
        'week',
        'shift',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        // nothing to hide
    ];

    /**
     * Get the user that owns the absence.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
