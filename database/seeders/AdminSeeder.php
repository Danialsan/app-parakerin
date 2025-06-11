<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $user1 = User::create([
            'name' => 'Admin 1',
            'email' => 'admin@gmail.com',
            'password' => '12345',
            'role' => 'admin'
        ]);

        Admin::create([
            'user_id' => $user1->id
        ]);

    }
}
