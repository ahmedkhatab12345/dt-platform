<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class InnovationFramework extends Model
{
    use HasFactory;

    protected $table = 'innovation_frameworks';

    protected $fillable = [
        'government_entity_id',
        'framework_name',
        'details',
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
