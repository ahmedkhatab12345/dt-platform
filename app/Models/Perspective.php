<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Perspective extends Model
{
    use HasFactory;

    protected $fillable = ['name','description','uuid'];

    public function pillars()
    {
        return $this->hasMany(Pillar::class)->orderBy('order');
    }
}
