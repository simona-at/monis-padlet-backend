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

        $user3 = new User();
        $user3->first_name = "Alina";
        $user3->last_name = "Ascher";
        $user3->email = "alina.ascher@gmx.at";
        $user3->password = bcrypt("secret");
        $user3->save();

        $user4 = new User();
        $user4->first_name = "Elmar";
        $user4->last_name = "Putz";
        $user4->email = "elmar.putz@fh-hagenberg.at";
        $user4->password = bcrypt("supersecret");
        $user4->save();

        $user5 = new User();
        $user5->first_name = "Johannes";
        $user5->last_name = "Schönböck";
        $user5->email = "johannes.schoenboeck@fh-hagenberg.at";
        $user5->password = bcrypt("superdupersecret");
        $user5->save();
    }
}
