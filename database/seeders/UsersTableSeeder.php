<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user1 = new User();
        $user1->first_name = "Simona";
        $user1->last_name = "Ascher";
        $user1->email = "hallo@simona.at";
        $user1->password = bcrypt("secret");
        $user1->save();

        $user2 = new User();
        $user2->first_name = "Michael";
        $user2->last_name = "Keplinger";
        $user2->email = "hallo@mjk-media.com";
        $user2->password = bcrypt("secret");
        $user2->save();
    }
}
