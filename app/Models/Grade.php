<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Grade extends Model
{
    protected $fillable = [
        'user_id',
        'course_id',
        'eval1',
        'eval2',
        'eval3',
        'eval4',
        'grade',
        'period',
    ];

    protected $casts = [
        'eval1' => 'float',
        'eval2' => 'float',
        'eval3' => 'float',
        'eval4' => 'float',
        'grade' => 'float',
    ];

    protected static function booted()
    {
        static::saving(function ($grade) {
            $evals = [
                (float) $grade->eval1,
                (float) $grade->eval2,
                (float) $grade->eval3,
                (float) $grade->eval4,
            ];
            
            // Calculate average in base 100
            $sum = array_sum($evals);
            $count = count($evals);
            $avg100 = $sum / $count;
            
            // Convert to base 20: (avg100 * 20) / 100 => avg100 / 5
            $grade->grade = $avg100 / 5;
        });
    }

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
