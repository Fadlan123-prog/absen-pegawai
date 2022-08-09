<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = new User;
        $user->name = 'admin';
        $user->username = 'admin';
        $user->phone = "081234556";
        $user->photo_url = null;
        $user->photo_path = null;
        $user->password = Hash::make('satu23empat');
        $user->save();
        $user->assignRole('admin');

        $user = new User;
        $user->name = 'Dindra';
        $user->username = 'Dindra';
        $user->phone = "088765432";
        $user->photo_url = null;
        $user->photo_path = null;
        $user->password = Hash::make('satu23empat');
        $user->save();
        $user->assignRole('user');

    }
}
