<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendanceSummary extends Model
{
    use HasFactory;

    protected $fillable = [
        'campus',
        'class_name',
        'month',
        'total_students',
        'total_present',
        'total_absent',
        'attendance_percentage',
    ];

    protected function casts(): array
    {
        return [
            'month' => 'date',
            'total_students' => 'integer',
            'total_present' => 'integer',
            'total_absent' => 'integer',
            'attendance_percentage' => 'decimal:2',
        ];
    }
}
