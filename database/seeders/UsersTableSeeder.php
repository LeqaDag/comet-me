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
            'phone' => "0598476334",
            'type' => 1,
            'is_admin' => 1
        ));
        User::create(array(
            'name'     => 'Leqa Daghameen',
            'email'    => 'leqa@comet-me.org',
            'password' => Hash::make('Leqa@2023'),
            'gender' => "female",
            'image' => "hh",
            'phone' => "0509338554",
            'type' => 1,
            'is_admin' => 1
        ));
        User::create(array(
            'name'     => 'Tamar Cohen',
            'email'    => 'tamar@comet-me.org',
            'password' => Hash::make('Tamar@2023'),
            'gender' => "female",
            'image' => "hh",
            'phone' => "0544582376",
            'type' => 1,
            'is_admin' => 1
        ));
        User::create(array(
            'name'     => 'Asmahan Simry',
            'email'    => 'asmahan@comet-me.org',
            'password' => Hash::make('Asmahan@2023'),
            'gender' => "female",
            'image' => "hh",
            'phone' => "0523930440",
            'type' => 1,
            'is_admin' => 1
        ));
        User::create(array(
            'name'     => 'Elad Orian',
            'email'    => 'elad@comet-me.org',
            'password' => Hash::make('Elad@2023'),
            'gender' => "male",
            'image' => "hh",
            'phone' => "0523930440",
            'type' => 1,
            'is_admin' => 1
        ));
        User::create(array(
            'name'     => 'Nidal Safarini',
            'email'    => 'nidal@comet-me.org',
            'password' => Hash::make('Nidal@2023'),
            'gender' => "male",
            'image' => "hh",
            'phone' => "0585124246",
            'type' => 1,
            'is_admin' => 0
        ));
        User::create(array(
            'name'     => 'Waseem al-Jaabari',
            'email'    => 'waseem@comet-me.org',
            'password' => Hash::make('Waseem@2023'),
            'gender' => "male",
            'image' => "hh",
            'phone' => "0526769174",
            'type' => 1,
            'is_admin' => 1
        ));
        User::create(array(
            'name'     => 'Dahham Abu Aram',
            'email'    => 'dahham@comet-me.org',
            'password' => Hash::make('Dahham@2023'),
            'gender' => "male",
            'image' => "hh",
            'phone' => "0523912599",
            'type' => 1,
            'is_admin' => 0
        ));
        User::create(array(
            'name'     => 'Arafat Arafat',
            'email'    => 'arafat@comet-me.org',
            'password' => Hash::make('Arafat@2023'),
            'gender' => "male",
            'image' => "hh",
            'phone' => "0587727566",
            'type' => 1,
            'is_admin' => 0
        ));
        User::create(array(
            'name'     => 'Musab Shrouf',
            'email'    => 'musab@comet-me.org',
            'password' => Hash::make('Musab@2023'),
            'gender' => "male",
            'image' => "hh",
            'phone' => "0587374732",
            'type' => 1,
            'is_admin' => 0
        ));
        User::create(array(
            'name'     => 'Sujood Abusabha',
            'email'    => 'sujood@comet-me.org',
            'password' => Hash::make('Sujood@2023'),
            'gender' => "female",
            'image' => "hh",
            'phone' => "0598795616",
            'type' => 1,
            'is_admin' => 0
        ));
        User::create(array(
            'name'     => 'Moatasem Hathaleen',
            'email'    => 'almutasm@comet-me.org',
            'password' => Hash::make('Moatasem@2023'),
            'gender' => "male",
            'image' => "hh",
            'phone' => "0524459676",
            'type' => 1,
            'is_admin' => 0
        ));
    }
}
