<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Language extends Model
{
    protected $fillable = [
        'code',
        'name',
        'formula',
        'regex_pattern',
        'description',
        'valid_examples',
        'invalid_examples',
        'is_active',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'valid_examples' => 'array',
            'invalid_examples' => 'array',
            'is_active' => 'boolean',
        ];
    }

    #[Scope]
    protected function active(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    #[Scope]
    protected function ordered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderBy('id');
    }

    public function selectedConversationMessages(): HasMany
    {
        return $this->hasMany(ConversationMessage::class, 'selected_language_id');
    }

    public function matchedConversationMessages(): HasMany
    {
        return $this->hasMany(ConversationMessage::class, 'matched_language_id');
    }
}
