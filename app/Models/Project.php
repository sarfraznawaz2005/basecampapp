<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = [
        'user_id',
        'project_id',
        'project_name',
        'hours',
    ];

    /**
     * Get's user of the project from relationship
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
