<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AccountSeeder extends Seeder
{
    public function run(): void
    {
        Account::create([
            'name' => 'testuser',
            'email' => 'test@examle.com',
            'password' => Hash::make('password'),
        ]);
    }
}
