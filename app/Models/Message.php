<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = [
        'chat_channel_id',
        'text',
        'file_path'
    ];

    public function chatChannel()
    {
        return $this->belongsTo(ChatChannel::class, 'chat_channel_id');
    }

}
