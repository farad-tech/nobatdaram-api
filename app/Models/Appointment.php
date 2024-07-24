<?php

namespace App\Models;

use App\Http\Controllers\User\ProfileController;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'appointable_id',
        'appointable_type',
        'start_at',
        'end_at',
    ];

    protected $casts = [
        'start_at' => 'datetime',
        'end_at' => 'datetime',
    ];

    protected $appends = ['profile', 'gotten_from_profile'];

    protected function profile(): Attribute
    {
        return new Attribute(
            get: function () {
                $profileController = new ProfileController;
                return $profileController->retriveProfile($this->appointable_id);

            }
        );
    }

    protected function gottenFromProfile(): Attribute
    {
        return new Attribute(
            get: function () {
                $profileController = new ProfileController;
                return $profileController->retriveProfile($this->user_id);

            }
        );
    }
}
