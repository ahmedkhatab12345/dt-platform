<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pillar extends Model
{
    use HasFactory;

    protected $fillable = ['perspective_id','name','description','uuid'];

    public function perspective()
    {
        return $this->belongsTo(Perspective::class);
    }

    public function standards()
    {
        return $this->hasMany(Standard::class)->orderBy('order');
    }
}
