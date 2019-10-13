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
        $table = ['Administration', 'Supprimer', 'GestionDesSeries', 'GestionDesFichiers', 'GestionDesUsers', 'GestionDesNews', 'GestionDesGenres', 'GestionDesCommentaires', 'GestionDesAvancements', 'Gestion'];
        foreach ($table as $name){
            $permission = \Spatie\Permission\Models\Permission::create([
                'name' => $name,
                'guard_name' => 'web'
            ]);
        }
        $grade = \Spatie\Permission\Models\Role::create([
            'name' => 'Webmaster',
            'guard_name' => 'web'
        ]);
        $permission = \Spatie\Permission\Models\Permission::all()->pluck('id')->toArray();
        $grade->sync($permission);
        $grade->save();

        $user = new User();
        $user->name = 'rougeXIII';
        $user->email = 'squall_djidane@msn.com';
        $user->password =  bcrypt('123456');
        $user->remember_token = str_random(10);
        $user->theme = 'light';
        $user->avatar = 'https://avatarfiles.alphacoders.com/905/thumb-90595.gif';
        $user->save();

        $user->roles()->sync($grade->id);
        $user->save();
    }
}
