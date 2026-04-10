<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Visit extends Model
{
    protected $fillable = [
        'store_id',
        'user_id',
        'visit_plan_id',
        'visited_at',
        'memo',
    ];

    public function visitPlan()
    {
        return $this->belongsTo(VisitPlan::class);
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }
}
