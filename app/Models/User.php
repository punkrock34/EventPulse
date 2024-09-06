<?php

namespace App\Models;

use App\Constants\DatabaseTables;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = DatabaseTables::USERS->value;

    protected $primaryKey = 'id';

    public $incrementing = true;

    protected $keyType = 'int';

    protected $fillable = [
        'id',
        'firebase_uid',
        'name',
        'email',
        'email_verified_at',
        'avatar',
    ];

    protected $hidden = [
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Get the events created by the user.
     */
    public function events()
    {
        return $this->hasMany(Event::class, 'owner_id');
    }

    /**
     * Get the events the user has joined.
     */
    public function joinedEvents()
    {
        return $this->belongsToMany(Event::class, DatabaseTables::EVENT_PARTICIPANTS->value, 'user_id', 'event_id')
            ->withTimestamps();
    }

    /**
     * Check if the user has a specific role.
     */
    public function hasRole(string $role): bool
    {
        return $this->role === $role;
    }
}
