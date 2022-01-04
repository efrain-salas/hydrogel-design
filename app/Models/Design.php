<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Builder;

class Design extends Model
{
    use HasFactory;

    protected static function booted()
    {
        static::addGlobalScope('default', function (Builder $builder) {
            $user = auth()->user();
            if ( ! $user->isAdmin()) {
                $builder->where('user_id', $user->id);
            }
        });
    }

    // Relationships

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Accessors & Mutators

    public function getDownloadUrlAttribute(): string
    {
        return Storage::url($this->file);
    }
}
