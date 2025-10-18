<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Standard extends Model
{
    use HasFactory;

    protected $fillable = ['pillar_id','name','criteria','weight','uuid'];

    public function pillar()
    {
        return $this->belongsTo(Pillar::class);
    }

    public function assignments()
    {
        return $this->hasMany(Assignment::class);
    }

    public function projects()
    {
        return $this->hasMany(Project::class);
    }
}
