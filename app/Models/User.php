<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function targets()
    {
        return $this->hasMany(Target::class);
    }

    public function notificationChannels()
    {
        return $this->hasMany(NotificationChannel::class);
    }

    /**
     * Slug unik untuk halaman status publik.
     * Contoh: /status/johndoe
     */
    public function statusPageSlug(): string
    {
        return strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $this->name));
    }
}
