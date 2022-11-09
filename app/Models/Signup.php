<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Signup extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $hidden = [
        'token',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $guarded = ['id', 'verfied_at', 'created_at', 'updated_at', 'deleted_at'];

    protected $casts = [
        'is_mentor' => 'boolean',
        'country_id' => 'integer',
    ];

    public function industry()
    {
        return $this->belongsTo(Category::class, 'industry_id');
    }
}
