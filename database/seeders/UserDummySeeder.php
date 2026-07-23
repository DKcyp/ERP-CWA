<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserDummySeeder extends Seeder
{
    public function run(): void
    {
        $role = Role::where('role_code', 'user')->first();

        User::create([
            'name'     => 'User Dummy',
            'username' => 'dummy',
            'password' => bcrypt('password123'),
            'role_id'  => $role->id,
        ]);

        $this->command->info('User dummy created: username=dummy, password=password123');
    }
}
