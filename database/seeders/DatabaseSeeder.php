<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call(BookstoreSeeder::class);

        Role::updateOrCreate(
            ['id' => 1],
            ['name' => 'Customer', 'type' => 'public', 'created_by' => 'system']
        );

        Role::updateOrCreate(
            ['id' => 2],
            ['name' => 'Developer', 'type' => 'private', 'created_by' => 'system']
        );

        $admin = User::where('username', 'admin')->first()
            ?? User::where('role_id', 2)->first();

        $adminData = [
            'name' => 'Admin',
            'username' => 'admin',
            'email' => 'admin@leemarblegallery.com',
            'mobile' => '0000000000',
            'password' => Hash::make('123456'),
            'role_id' => 2,
            'status' => 'active',
            'mobile_verified' => 1,
            'email_verified' => 1,
        ];

        if ($admin) {
            $admin->update($adminData);
        } else {
            User::create($adminData);
        }
    }
}
