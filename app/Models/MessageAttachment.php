<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\MessageAttachment
 *
 * @property integer $id
 * @property integer $type
 * @property string $filename
 * @property string $path
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */

class MessageAttachment extends Model
{
    use HasFactory;

    public const TYPES = [
        'image' => 1,
        'video' => 2,
        'file' => 3,
    ];

    public function isImage() {
        return $this->type == self::TYPES['image'];
    }

    public function isVideo() {
        return $this->type == self::TYPES['video'];
    }

    public function isFile() {
        return $this->type == self::TYPES['file'];
    }

    public function getNamedTypeAttribute() {
        return self::TYPES[ $this->type ];
    }

    public function message() {
        return $this->belongsTo(Message::class);
    }
}
