<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserFcmToken extends Model
{
    use HasFactory;

    public $casts = [
        'token' => 'encrypted'
    ];

    public $fillable = [
        'token'
    ];
}
