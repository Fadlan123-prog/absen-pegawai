<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Attendances extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'clockin',
        'clockout',
        'user_name',
        'user_id',
        'status_in',
        'status_out',
        'lat',
        'lng',
        'attendance_photo_path',
        'attendance_photo_url',
        'is_on_time'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
