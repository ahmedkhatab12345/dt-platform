<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    use HasFactory;

    protected $table = 'entity_standard_tool';
    protected $fillable = ['standard_id', 'tool_id', 'government_entity_id'];

    public function standard()
    {
        return $this->belongsTo(Standard::class);
    }

    public function tool()
    {
        return $this->belongsTo(Tool::class);
    }

    public function governmentEntity()
    {
        return $this->belongsTo(GovernmentEntity::class, 'government_entity_id');
    }
}
