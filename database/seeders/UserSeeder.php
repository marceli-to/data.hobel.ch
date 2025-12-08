<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'Marcel Stadelmann',
                'email' => 'm@marceli.to',
                'password' => '7aq31rr23',
            ],
            [
                'name' => 'Benedikt Flüeler',
                'email' => 'bf@wbg.ch',
                'password' => 'bfhd-d@t@-3228',
            ],
            [
                'name' => 'Lutz Kögler',
                'email' => 'koegler@hobel.ch',
                'password' => 'lkhd-d2t2-4387',
            ],
        ];

        foreach ($users as $userData) {
            if (!User::where('email', $userData['email'])->exists()) {
                User::create([
                    'name' => $userData['name'],
                    'email' => $userData['email'],
                    'password' => Hash::make($userData['password']),
                ]);
            }
        }
    }
}
