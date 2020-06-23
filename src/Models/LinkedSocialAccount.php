<?php

namespace MedianetDev\LaravelAuthApi\Models;

use Illuminate\Database\Eloquent\Model;

class LinkedSocialAccount extends Model
{
    protected $fillable = [
        'provider_name',
        'provider_id',
        'email',
    ];

    public function user()
    {
        return $this->belongsTo(ApiUser::class);
    }
}
