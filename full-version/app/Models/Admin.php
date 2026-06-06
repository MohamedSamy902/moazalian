<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class Admin extends Authenticatable
{
    use SoftDeletes, Notifiable, HasRoles;

    protected $guarded = [];
    protected $hidden = ['password', 'remember_token'];
    protected $appends = ['avatar_url'];

    protected function avatarUrl(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->avatar
                ? asset('storage/' . $this->avatar)
                : 'https://ui-avatars.com/api/?name=' . urlencode($this->name ?? 'Admin') . '&background=7367f0&color=fff&rounded=true',
        );
    }
}
