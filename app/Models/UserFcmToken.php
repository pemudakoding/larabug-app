<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserFcmToken extends Model
{
    use HasFactory;

    public $casts = [
        'token' => 'encrypted',
        'device' => 'array',
    ];

    public $fillable = [
        'token',
        'device',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
