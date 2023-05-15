<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Padlet extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description'];

    /**
     * padlet has zero, one or more images
     * @return HasMany
     */
    public function images() : HasMany {
        return $this->hasMany(Image::class);
    }

    /**
     * padlet has zero, one or more likes
     * @return HasMany
     */
    public function likes() : HasMany {
        return $this->hasMany(Like::class);
    }

    /**
     * padlet has zero, one or more comments
     * @return HasMany
     */
    public function comments() : HasMany {
        return $this->hasMany(Comment::class);
    }

    /**
     * padlet has zero, one or more users
     * @return BelongsToMany
     */
    public function users() : BelongsToMany {
        return $this->belongsToMany(User::class)->withTimestamps()->withPivot('user_role');
    }

}
