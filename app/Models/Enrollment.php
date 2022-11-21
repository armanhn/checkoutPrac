<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enrollment extends Model
{
    use HasFactory;

    protected $fillable =      [
        'student_name',
        'student_email',
        'school_attended',
        'gender',
        'dob',
        'student_cell',
        'home_phone',
        'address',
        'parent_name',
        'parent_email',
        'parent_cell',
    ];
}
