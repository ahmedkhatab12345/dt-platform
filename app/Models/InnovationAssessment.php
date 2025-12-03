<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InnovationAssessment extends Model
{
    use HasFactory;

    protected $table = 'innovation_assessments';

    protected $fillable = [
        'government_entity_id',
        'summary',
        'strengths',
        'weaknesses',
        'recommendation',
        'understanding_level',
        'created_by',
    ];

    protected $casts = [
        'government_entity_id' => 'integer',
        'created_by' => 'integer',
    ];

    public function governmentEntity()
    {
        return $this->belongsTo(GovernmentEntity::class, 'government_entity_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
