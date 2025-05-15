<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserAddresses extends Model
{
    use HasFactory;

    function user() {
        return $this->belongsTo(User::class);
    }
}
