<?php

namespace App\Models;

use App\Enums\GovernmentEntityClassification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GovernmentEntity extends Model
{
    use HasFactory;

    protected $fillable = ['uuid','name','classification'];

    protected $casts = [
        'classification' => GovernmentEntityClassification::class,
    ];

    public function assignments()
    {
        return $this->hasMany(Assignment::class, 'government_entity_id');
    }

    public function projects()
    {
        return $this->hasMany(Project::class);
    }
}
