<?php

namespace App\Models;

use App\Models\Scopes\UserScope;
use Database\Factories\JogoFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Jogo extends Model
{
    /** @use HasFactory<JogoFactory> */
    use HasFactory;

    protected $fillable = ['user_id', 'jogo', 'n1', 'n2', 'n3', 'n4', 'n5', 'n6', 'n7', 'n8', 'n9', 'n10', 'n11', 'n12', 'force'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    protected static function booted(): void
    {
        static::addGlobalScope(new UserScope);
    }
}
