<?php

namespace Database\Seeders;

use App\Models\_user_roles;
use App\Models\Users;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class userRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        _user_roles::insert([
            [
                'ROLE_NAME' => 'RM_ADMINISTRATOR',
            ],
            [
                'ROLE_NAME' => 'RM_GUARDIAN',
            ],
            [
                'ROLE_NAME' => 'RM_TEACHER',
            ],
            ]);

        Users::create([
            'U_NAME' => 'admin',
            'U_PASSWORD_HASH' => Hash::make( "1admin123"),
            'UR_ID' => 1, // RM_ADMINISTRATOR
        ]);
    }
}
