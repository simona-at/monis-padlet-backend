<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Like extends Model
{
    use HasFactory;

    protected $fillable = ['value'];

    /**
     * like belongs exactly to one padlet
     * @return BelongsTo
     */
    public function padlet() : BelongsTo {
        return $this->belongsTo(Padlet::class);
    }

}
