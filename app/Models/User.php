<?php
namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;


class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public $timestamps = false;

    protected $table = 'admn_users';

    protected $fillable = [
        'usernamee',
        'passwordd',
        'designation',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getAuthIdentifierName()
    {
        return 'id'; // not 'email'
    }

    public function getAuthPassword()
    {
        return $this->passwordd; // not 'password'
    }
    /**
     * Get the projects for the user.
     */
    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    /**
     * Get the tasks for the user.
     */
    public function tasks()
    {
        return $this->hasMany(Task::class)->whereRaw("FIND_IN_SET(?, user_id)", [auth()->id()]);
    }

    /**
     * Get the routines for the user.
     */
    public function routines()
    {
        return $this->hasMany(Routine::class);
    }

    /**
     * Get the notes for the user.
     */
    public function notes()
    {
        return $this->hasMany(Note::class);
    }

    /**
     * Get the calendar events for the user.
     */
    public function files()
    {
        return $this->hasMany(File::class);
    }

    public function reminders()
    {
        return $this->hasMany(Reminder::class);
    }

    public function projectMembers()
    {
        return $this->belongsToMany(Project::class, 'project_teams', 'user_id', 'project_id');
    }
    public function assignedTasks()
    {
        return Task::whereRaw("FIND_IN_SET(?, user_id)", [$this->id]);
    }
    public function headedProjects()
    {
        return $this->hasMany(Project::class, 'hod_id');
    }
    public function taskMessages()
    {
        return $this->hasMany(TaskMessage::class);
    }
}
