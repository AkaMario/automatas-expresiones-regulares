<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ConversationMessage extends Model
{
    protected $fillable = [
        'conversation_id',
        'selected_language_id',
        'matched_language_id',
        'user_message',
        'bot_message',
        'is_valid',
        'error_type',
        'suggestion',
        'validation_payload',
    ];

    protected function casts(): array
    {
        return [
            'is_valid' => 'boolean',
            'validation_payload' => 'array',
        ];
    }

    public function conversation(): BelongsTo
    {
        return $this->belongsTo(Conversation::class);
    }

    public function selectedLanguage(): BelongsTo
    {
        return $this->belongsTo(Language::class, 'selected_language_id');
    }

    public function matchedLanguage(): BelongsTo
    {
        return $this->belongsTo(Language::class, 'matched_language_id');
    }
}
