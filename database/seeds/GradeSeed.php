<?php

use Illuminate\Database\Seeder;
use App\User;

class GradeSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $grade = \Spatie\Permission\Models\Role::create([
            'Name' => 'Webmaster',
            'guard_name' => 'web'
        ]);
        $permission = \Spatie\Permission\Models\Permission::all()->pluck('id')->toArray();
        $grade->sync($permission);
        $grade->save();
    }
}
