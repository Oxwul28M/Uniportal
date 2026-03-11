<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Post extends Model
{
    protected $fillable = [
        'title',
        'content',
        'category',
        'image_url',
        'event_date',
        'event_location',
        'is_published',
        'user_id',
    ];

    protected $casts = [
        'event_date' => 'date',
        'is_published' => 'boolean',
    ];

    /** Relación: un post pertenece a un usuario (quien lo publicó). */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /** Scope: solo publicados. */
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    /** Scope: solo noticias. */
    public function scopeNoticias($query)
    {
        return $query->where('category', 'noticia');
    }

    /** Scope: solo eventos. */
    public function scopeEventos($query)
    {
        return $query->where('category', 'evento');
    }
}
