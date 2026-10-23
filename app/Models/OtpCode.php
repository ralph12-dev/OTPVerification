<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OtpCode extends Model
{
    protected $fillable = ['email', 'code', 'expires_at', 'attempts'];

    protected $hidden = ['code'];

    protected function casts(): array
    {
        return [
            'expires_at' => 'datetime',
        ];
    }
}
