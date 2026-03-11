<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Grade extends Model
{
    protected $fillable = [
        'user_id',
        'course_id',
        'grade',
        'period',
    ];

    protected $casts = [
        'grade' => 'float',
    ];

    /** Relación: una calificación pertenece a un estudiante. */
    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /** Relación: una calificación pertenece a un curso. */
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }
}
