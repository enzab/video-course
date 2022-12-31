<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::create([
            'name' => 'Sabri',
            'username' => 'ensabrii',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('Sandi123'),
        ]);

        $role = Role::find(1);

        $user->assignRole($role);
    }
}
