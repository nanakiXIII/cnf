<?php

use Illuminate\Database\Seeder;
use App\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = new User();
        $user->name = 'rougeXIII';
        $user->email = 'squall_djidane@msn.com';
        $user->password =  bcrypt('123456');
        $user->remember_token = str_random(10);
        $user->save();

    }
}
