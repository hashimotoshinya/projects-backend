<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VisitPlan extends Model
{
    protected $fillable = [
        'user_id',
        'store_id',
        'visit_date',
        'memo',
    ];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function visit()
    {
        return $this->hasOne(Visit::class);
    }
}