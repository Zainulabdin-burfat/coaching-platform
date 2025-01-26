<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Scout\Searchable;

class Client extends Model
{
    use Searchable;
    protected $fillable = ['user_id', 'coach_id', 'progress'];

    public function toSearchableArray(): array
    {
        return [
            'id' => $this->id,
            'coach_id' => $this->coach_id,
            'progress' => $this->progress,
        ];
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function coach(): BelongsTo
    {
        return $this->belongsTo(Coach::class);
    }

    public function sessions(): HasMany
    {
        return $this->hasMany(Session::class);
    }
}
