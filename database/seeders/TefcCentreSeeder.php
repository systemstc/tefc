<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\User;
use App\Models\TefcCentre;
use Illuminate\Support\Facades\Hash;

class TefcCentreSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Roles
        $centerRole = Role::firstOrCreate(
            ['slug' => 'center'],
            ['name' => 'TEFC Centre Officer', 'description' => 'Officer managing exporter validation and cluster facilitation for a specific TEFC Centre']
        );

        Role::firstOrCreate(
            ['slug' => 'exporter'],
            ['name' => 'Exporter', 'description' => 'Textile Exporter / Manufacturer']
        );

        // 2. Create the 6 Centre Users
        $centres = TefcCentre::all();

        foreach ($centres as $code => $data) {
            $user = User::updateOrCreate(
                ['email' => $data['email']],
                [
                    'name' => "TEFC {$data['name']} Centre",
                    'password' => Hash::make('password'),
                    'status' => 'active',
                    'tefc_centre' => $code,
                ]
            );

            if (!$user->roles()->where('slug', 'center')->exists()) {
                $user->roles()->attach($centerRole);
            }
        }
    }
}
