<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Course extends Model
{
    protected $fillable = [
        'name',
        'code',
        'teacher_name',
        'teacher_id',
        'section',
        'max_students',
    ];

    /** Relación: un curso pertenece a un profesor (User). */
    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    /** Relación: alumnos inscritos en el curso. */
    public function students(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'enrollments', 'course_id', 'student_id')
                    ->withPivot('period')
                    ->withTimestamps();
    }

    /** Relación: calificaciones de este curso. */
    public function grades(): HasMany
    {
        return $this->hasMany(Grade::class);
    }
}
