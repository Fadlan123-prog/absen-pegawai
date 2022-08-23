<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendances extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'user_id',
        'status',
        'lat',
        'lng',
        'attendance_photo_path',
        'attendance_photo_url',
        'is_on_time'
    ];
}
