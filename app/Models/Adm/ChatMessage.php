<?php

namespace App\Models\Adm;

class ChatMessage extends AdmModel
{
    protected $table = 'chat_messages';

    protected function casts(): array
    {
        return [
            'is_first' => 'boolean',
            'is_read' => 'boolean',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }
}
