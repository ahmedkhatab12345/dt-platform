<?php

namespace App\Models;

use App\Enums\GovernmentEntityClassification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GovernmentEntity extends Model
{
    use HasFactory;

    protected $fillable = ['uuid','name','classification','created_by'];

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

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
