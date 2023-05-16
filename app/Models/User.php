<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'email',
        'password',
        'first_name',
        'last_name'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * user has zero, one or more padlets
     * @return BelongsToMany
     */
    public function padlets() : BelongsToMany{
        return $this->belongsToMany(Padlet::class)->withTimestamps()->withPivot('user_role');
    }

    /**
     * user has zero, one or more comments
     * @return HasMany
     */
    public function comments() : HasMany {
        return $this->hasMany(Comment::class);
    }

    /**
     * user has zero, one or more likes
     * @return HasMany
     */
    public function likes() : HasMany {
        return $this->hasMany(Like::class);
    }
}
