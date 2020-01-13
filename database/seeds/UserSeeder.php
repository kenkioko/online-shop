<?php

use Illuminate\Database\Seeder;
use App\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert(array(
            array(
                'name'=>'admin',
                'email' => 'admin@admin.com',
                'password' => bcrypt('password'),
                'email_verified_at'=> date("Y-m-d H:i:s"),
                'created_at' => date("Y-m-d H:i:s"),
            ),
            array(
                'name'=>'johndoe',
                'email' => 'john@doe.com',
                'password' => bcrypt('password'),
                'email_verified_at'=> date("Y-m-d H:i:s"),
                'created_at' => date("Y-m-d H:i:s"),
            ),
        ));

        User::where('email','admin@admin.com')
            ->first()
            ->assignRole('admin');

        User::where('email','john@doe.com')
            ->first()
            ->assignRole('buyer');
    }
}
