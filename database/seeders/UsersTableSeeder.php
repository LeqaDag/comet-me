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
            'email'    => 'leqa@comet-me.org',
            'password' => Hash::make('Leqa@2023'),
            'gender' => "female",
            'image' => "2.png",
            'phone' => "0509338554",
            'user_type_id' => 1,
            'is_admin' => 1
        ));
        User::create(array(
            'name'     => 'Tamar Cohen',
            'email'    => 'tamar@comet-me.org',
            'password' => Hash::make('Tamar@2023'),
            'gender' => "female",
            'image' => "2.png",
            'phone' => "0544582376",
            'user_type_id' => 1,
            'is_admin' => 1
        ));
        User::create(array(
            'name'     => 'Asmahan Simry',
            'email'    => 'asmahan@comet-me.org',
            'password' => Hash::make('Asmahan@2023'),
            'gender' => "female",
            'image' => "4.png",
            'phone' => "0523930440",
            'user_type_id' => 2,
            'is_admin' => 1
        ));
        User::create(array(
            'name'     => 'Elad Orian',
            'email'    => 'elad@comet-me.org',
            'password' => Hash::make('Elad@2023'),
            'gender' => "male",
            'image' => "9.png",
            'phone' => "0523930440",
            'user_type_id' => 2,
            'is_admin' => 1
        ));
        User::create(array(
            'name'     => 'Nidal Safarini',
            'email'    => 'nidal@comet-me.org',
            'password' => Hash::make('Nidal@2023'),
            'gender' => "male",
            'image' => "5.png",
            'phone' => "0585124246",
            'user_type_id' => 5,
            'is_admin' => 0
        ));
        User::create(array(
            'name'     => 'Waseem al-Jaabari',
            'email'    => 'waseem@comet-me.org',
            'password' => Hash::make('Waseem@2023'),
            'gender' => "male",
            'image' => "7.png",
            'phone' => "0526769174",
            'user_type_id' => 1,
            'is_admin' => 1
        ));
        User::create(array(
            'name'     => 'Dahham Abu Aram',
            'email'    => 'dahham@comet-me.org',
            'password' => Hash::make('Dahham@2023'),
            'gender' => "male",
            'image' => "13.png",
            'phone' => "0523912599",
            'user_type_id' => 4,
            'is_admin' => 0
        ));
        User::create(array(
            'name'     => 'Arafat Arafat',
            'email'    => 'arafat@comet-me.org',
            'password' => Hash::make('Arafat@2023'),
            'gender' => "male",
            'image' => "12.png",
            'phone' => "0587727566",
            'user_type_id' => 6,
            'is_admin' => 0
        )); 
        User::create(array(
            'name'     => 'Musab Shrouf',
            'email'    => 'musab@comet-me.org',
            'password' => Hash::make('Musab@2023'),
            'gender' => "male",
            'image' => "14.png",
            'phone' => "0587374732",
            'user_type_id' => 3,
            'is_admin' => 0
        ));
        User::create(array(
            'name'     => 'Sujood Abusabha',
            'email'    => 'sujood@comet-me.org',
            'password' => Hash::make('Sujood@2023'),
            'gender' => "female",
            'image' => "18.png",
            'phone' => "0598795616",
            'user_type_id' => 9,
            'is_admin' => 0
        ));
        User::create(array(
            'name'     => 'Moatasem Hathaleen',
            'email'    => 'almutasm@comet-me.org',
            'password' => Hash::make('Moatasem@2023'),
            'gender' => "male",
            'image' => "17.png",
            'phone' => "0524459676",
            'user_type_id' => 11,
            'is_admin' => 0
        ));
        User::create(array(
            'name'     => 'Ibrahim Makhamreh',
            'email'    => 'ibrahim@comet-me.org',
            'password' => Hash::make('Ibrahim@2023'),
            'gender' => "male",
            'phone' => "0567271774",
            'image' => "17.png",
            'user_type_id' => 7,
            'is_admin' => 0
        ));
        User::create(array(
            'name'     => 'Mamoun',
            'email'    => 'mamoun@comet-me.org',
            'password' => Hash::make('Mamoun@2023'),
            'gender' => "male",
            'image' => "17.png",
            'phone' => "0524459676",
            'user_type_id' => 12,
            'is_admin' => 0
        ));
        User::create(array(
            'name'     => 'Ibrahim Awlad Ahmad',
            'email'    => 'ibrahim.m@comet-me.org',
            'password' => Hash::make('IbrahimAwlad@2023'),
            'gender' => "male",
            'image' => "12.png",
            'phone' => "0568271456",
            'user_type_id' => 10,
            'is_admin' => 0
        )); 
        User::create(array(
            'name'     => 'Anas Ghannam',
            'email'    => 'anas@comet-me.org',
            'password' => Hash::make('Anas@2023'),
            'gender' => "male",
            'image' => "12.png",
            'phone' => "0592479428",
            'user_type_id' => 10,
            'is_admin' => 0
        ));
        User::create(array(
            'name'     => 'Ahmad Najadah',
            'email'    => 'ahmadnajadeh@comet-me.org',
            'password' => Hash::make('Ahmad@2023'),
            'gender' => "male",
            'image' => "12.png",
            'phone' => "0587910062",
            'user_type_id' => 7,
            'is_admin' => 0
        ));
        User::create(array(
            'name'     => 'Ameer Saaydeh',
            'email'    => 'ameer@comet-me.org',
            'password' => Hash::make('Ameer@2023'),
            'gender' => "male",
            'image' => "12.png",
            'phone' => "0598451467",
            'user_type_id' => 7,
            'is_admin' => 0
        ));
        User::create(array(
            'name'     => 'Ahmad Najadah',
            'email'    => 'ahmadnajadeh@comet-me.org',
            'password' => Hash::make('Ahmad@2023'),
            'gender' => "male",
            'image' => "12.png",
            'phone' => "0592479428",
            'user_type_id' => 7,
            'is_admin' => 0
        ));
    }
}
