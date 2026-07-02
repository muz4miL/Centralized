<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeeSummary extends Model
{
    use HasFactory;

    protected $fillable = [
        'campus',
        'month',
        'total_expected',
        'total_collected',
        'total_outstanding',
        'collection_percentage',
        'students_paid',
        'students_unpaid',
    ];

    protected function casts(): array
    {
        return [
            'month' => 'date',
            'total_expected' => 'decimal:2',
            'total_collected' => 'decimal:2',
            'total_outstanding' => 'decimal:2',
            'collection_percentage' => 'decimal:2',
            'students_paid' => 'integer',
            'students_unpaid' => 'integer',
        ];
    }
}
