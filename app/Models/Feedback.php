<?php

namespace App\Models;

use Kblais\Uuid\Uuid;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Feedback extends Model
{
    use Uuid,
        Filterable,
        HasFactory;

    protected $fillable = [
        'name',
        'email',
        'feedback'
    ];

    protected $appends = [
        'avatar'
    ];

    public function exception()
    {
        return $this->belongsTo(Exception::class);
    }

    public function getAvatarAttribute()
    {
        return $this->getGravatar();
    }

    public function getGravatar($size = 150)
    {
        return 'https://www.gravatar.com/avatar/' . md5(strtolower(trim($this->attributes['email']))) . '?s=' . (int)$size;
    }
}
