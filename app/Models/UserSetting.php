<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserSetting extends Model
{
    use HasFactory;
    protected $fillable = ['user_id','preference'];
    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }
    public function getPreferenceAttribute($value)
    {
        if ($value === null || $value === '') {
            return null;
        }
        $decodedValue = json_decode($value, true);
        if (json_last_error() === JSON_ERROR_NONE) {
            return $decodedValue;
        }
        return null;
    }
}

