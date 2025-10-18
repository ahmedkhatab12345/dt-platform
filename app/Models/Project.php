<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'overview',
        'start_date',
        'end_date',
        'budget',
        'status',
        'department',
        'indicators',
        'final_deliverables',
        'activities',
        'government_entity_id',
        'standard_id'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'budget' => 'decimal:2',
        'indicators' => 'array',
        'final_deliverables' => 'array',
        'activities' => 'array',
    ];

    // Enum for project status
    public const STATUS_PLANNED = 'planned';
    public const STATUS_IN_PROGRESS = 'in_progress';
    public const STATUS_COMPLETED = 'completed';

    public static function getStatuses(): array
    {
        return [
            self::STATUS_PLANNED => 'مخطط',
            self::STATUS_IN_PROGRESS => 'جاري التنفيذ',
            self::STATUS_COMPLETED => 'مكتمل',
        ];
    }

    public function getStatusLabelAttribute(): string
    {
        return self::getStatuses()[$this->status] ?? $this->status;
    }

    public function getFormattedBudgetAttribute(): string
    {
        return number_format($this->budget, 2) . ' ريال سعودي';
    }

    public function governmentEntity()
    {
        return $this->belongsTo(GovernmentEntity::class);
    }

    public function standard()
    {
        return $this->belongsTo(Standard::class);
    }
}
