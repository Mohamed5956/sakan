<?php

namespace Database\Seeders;

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
        User::create([
            'name'      => 'مدير النظام',
            'email'     => 'admin@sakan.com',
            'password'  => bcrypt('password'),
            'is_admin'  => true,
        ]);
    }
}
