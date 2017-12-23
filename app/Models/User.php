<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'basecamp_org',
        'basecamp_api_key',
        'basecamp_api_user_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get's user's projects in which he has time entries
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function projects()
    {
        return $this->hasMany(Project::class)->where('hours', '>', 0);
    }

    /**
     * Gets all projects of user
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function projectsAll()
    {
        return $this->hasMany(Project::class);
    }

    /**
     * Gets all todos of user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function todos()
    {
        return $this->hasMany(Todo::class);
    }

    /**
     * Gets all posted todos of user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function postedTodos()
    {
        return $this->hasMany(Todo::class)->where('status', 'posted');
    }

    /**
     * Gets all pending todos of user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function pendingTodos()
    {
        return $this->hasMany(Todo::class)->where('status', 'pending');
    }
}
