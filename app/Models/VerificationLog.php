<?php

namespace App\Models;

use Auth;
use Illuminate\Database\Eloquent\Model;

class VerificationLog extends Model
{
    protected $table        = 'verification_logs';
    protected $guarded      = ['id'];

    public static function boot() {
        parent::boot();
        static::creating(function ($item) {
            $user                   = Auth::guard('api')->user();
            $item->user_id          = $user->id;
        });
    }
}
