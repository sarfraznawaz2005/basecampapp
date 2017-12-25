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

    public function isAdmin()
    {
        return $this->is_admin === 1;
    }

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
        return $this->hasMany(Todo::class)->with('project');
    }

    /**
     * Gets all posted todos of user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function postedTodos()
    {
        return $this->hasMany(Todo::class)->with('project')
            ->where('status', 'posted');
    }

    /**
     * Gets all pending todos of user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function pendingTodos()
    {
        return $this->hasMany(Todo::class)->with('project')
            ->where('status', 'pending');
    }

    public function pendingTodosToday()
    {
        return $this->hasMany(Todo::class)->with('project')
            ->where('dated', date('Y-m-d'))
            ->where('status', 'pending');
    }

    public function pendingTodosHoursToday()
    {
        $hours = 0;

        $todosToday = $this->pendingTodosToday;

        foreach ($todosToday as $todoToday) {
            $diff = getBCHoursDiff($todoToday->dated, $todoToday->time_start, $todoToday->time_end);

            $hours += $diff;
        }

        return $hours;
    }

    public function pendingTodosHours()
    {
        $hours = 0;

        $todosToday = $this->pendingTodos;

        foreach ($todosToday as $todoToday) {
            $diff = getBCHoursDiff($todoToday->dated, $todoToday->time_start, $todoToday->time_end);

            $hours += $diff;
        }

        return $hours;
    }

    public function postedTodosHours()
    {
        $hours = 0;

        $todosToday = $this->postedTodos;

        foreach ($todosToday as $todoToday) {
            $diff = getBCHoursDiff($todoToday->dated, $todoToday->time_start, $todoToday->time_end);

            $hours += $diff;
        }

        return $hours;
    }
}
