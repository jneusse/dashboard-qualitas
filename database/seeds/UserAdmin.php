<?php

use Illuminate\Database\Seeder;
use App\User;
use Carbon\Carbon;

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

        $dateTime = Carbon::now();
        $session_expire_on = $dateTime;

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
