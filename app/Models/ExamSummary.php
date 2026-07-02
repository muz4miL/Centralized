<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamSummary extends Model
{
    use HasFactory;

    protected $fillable = [
        'campus',
        'class_name',
        'exam_name',
        'exam_date',
        'total_students',
        'students_passed',
        'students_failed',
        'pass_percentage',
        'average_score',
        'highest_score',
        'lowest_score',
    ];

    protected function casts(): array
    {
        return [
            'exam_date' => 'date',
            'total_students' => 'integer',
            'students_passed' => 'integer',
            'students_failed' => 'integer',
            'pass_percentage' => 'decimal:2',
            'average_score' => 'decimal:2',
            'highest_score' => 'decimal:2',
            'lowest_score' => 'decimal:2',
        ];
    }
}
