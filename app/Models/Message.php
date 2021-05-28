<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Message
 *
 * @property integer $id
 * @property integer $type
 * @property string $title
 * @property string $text
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\MessageAttachment[] $attachments
 */

class Message extends Model
{
    use HasFactory;

    public const TYPES = [
        'sms' => 1,
        'email' => 2,
        'whatsapp' => 3,
    ];

    public function isSms() {
        return $this->type == self::TYPES['sms'];
    }

    public function isEmail() {
        return $this->type == self::TYPES['email'];
    }

    public function isWhatsapp() {
        return $this->type == self::TYPES['whatsapp'];
    }

    public function getNamedTypeAttribute() {
        return self::TYPES[ $this->type ];
    }

    public function attachments() {
        return $this->hasMany(MessageAttachment::class);
    }
}
