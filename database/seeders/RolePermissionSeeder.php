<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Define Permissions
        $permissions = [
            ['name' => 'Manage Content', 'slug' => 'manage_content'],
            ['name' => 'Manage Users', 'slug' => 'manage_users'],
            ['name' => 'Manage Clusters', 'slug' => 'manage_clusters'],
            ['name' => 'Manage News', 'slug' => 'manage_news'],
            ['name' => 'View Dashboard', 'slug' => 'view_dashboard'],
        ];

        foreach ($permissions as $p) {
            Permission::updateOrCreate(['slug' => $p['slug']], $p);
        }

        // Define Roles
        $adminRole = Role::updateOrCreate(['slug' => 'admin'], ['name' => 'Administrator']);
        $buyerRole = Role::updateOrCreate(['slug' => 'buyer'], ['name' => 'Buyer']);
        $sellerRole = Role::updateOrCreate(['slug' => 'seller'], ['name' => 'Seller']);
        $exporterRole = Role::updateOrCreate(['slug' => 'exporter'], ['name' => 'Exporter']);
        $operatorRole = Role::updateOrCreate(['slug' => 'operator'], ['name' => 'Data Entry Operator', 'description' => 'Operator for catalogue and product data entry under an Exporter']);

        // Assign all permissions to Admin
        $adminRole->permissions()->sync(Permission::all());

        // Create Default Admin User
        $adminUser = User::updateOrCreate(
            ['email' => 'admin@tefc.gov.in'],
            [
                'name' => 'TEFC Admin',
                'password' => Hash::make('admin123'),
            ]
        );

        $adminUser->roles()->sync([$adminRole->id]);
    }
}
