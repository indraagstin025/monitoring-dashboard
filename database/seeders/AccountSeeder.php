<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AccountSeeder extends Seeder
{
    /**
     * Seed default application accounts.
     */
    public function run(): void
    {
        $admin = User::query()->updateOrCreate(
            ['email' => 'admin@monitoring.test'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('password'),
            ],
        );

        $admin->forceFill([
            'email_verified_at' => now(),
        ])->save();
    }
}
