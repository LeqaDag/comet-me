<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->delete();
        User::create(array(
            'name'     => 'Leqa Daghameen',
            'email'    => 'leqa@gmail.com',
            'password' => Hash::make('Leqa@2022'),
            'gender' => "female",
            'image' => "hh",
            'phone' => "0598476334"
        ));
    }
}
