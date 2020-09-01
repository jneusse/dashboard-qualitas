<?php

use Illuminate\Database\Seeder;
use App\User;

class UserAdmin extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::truncate();

        date_default_timezone_set('America/Mexico_City');
        $dateTime = date('d-m-Y H:i');
        $session_expire_on = strtotime('+15 minutes', strtotime($dateTime));

        $admin = User::create([
            'name' => 'Kranon Admin',
            'email' => 'admin@kranon.com',
            'password' => Hash::make('adminadmin'),
            'status' => false,
            'session_expire_on' => $session_expire_on,
            'is_admin' => true,
        ]);
    }
}
