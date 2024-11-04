<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class Seederr extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        DB::table('users')->insert([
            [
                'username' => 'Admin',
                'email' => 'admin@gmail.com',
                'password' => bcrypt('1234'),
                'role' => 'admin',
            ],
            ]);
    }
}
