<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'action',
        'model',
        'description',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getFormattedDateAttribute()
    {
        return Carbon::parse($this->created_at)->format('Y-m-d H:i');
    }

    public function getActionLabelAttribute()
    {
        return match ($this->action) {
            'created' => 'إضافة',
            'updated' => 'تعديل',
            'deleted' => 'حذف',
            default   => ucfirst($this->action),
        };
    }
}
