<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;

    protected $fillable = [
        'avatar',
        'name',
        'user_id',
    ];

    protected $appends = ['phoneoremail'];

    protected function phoneoremail(): Attribute
    {
        return new Attribute(
            get: fn () => User::find($this->user_id)->phoneoremail,
        );
    }
}
