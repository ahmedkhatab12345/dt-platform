<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Strategy extends Model
{
    use HasFactory;
    
    protected $fillable = ['government_entity_id','mission','vision'];

    public function governmentEntity()
    {
        return $this->belongsTo(GovernmentEntity::class);
    }
}
