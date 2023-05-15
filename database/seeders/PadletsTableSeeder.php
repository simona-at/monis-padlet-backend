<?php

namespace Database\Seeders;

use App\Models\Image;
use App\Models\Padlet;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PadletsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $padlet1 = new Padlet();
        $padlet1->title = "Sammlung von Lieblingshundenamen";
        $padlet1->description = "Apollo, Jeanny, Charlie, Django, Snoopy, Emily, Cindy. Kommentiert gerne mit euren liebsten Hundenamen :)";
        $padlet1->created_at = date("Y-m-d H:i:s");
        $padlet1->updated_at = date("Y-m-d H:i:s");
        $padlet1->save();

        $image1 = new Image();
        $image1->url = "https://images.unsplash.com/photo-1517849845537-4d257902454a?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1335&q=80";
        $image1->title = "Der Hund meiner Nachbarin";

        $padlet1->images()->saveMany([$image1]);

        $user = User::all()->where('first_name', "=", "Simona");
        $padlet1->users()->sync($user);
        $padlet1->save();


        $padlet2 = new Padlet();
        $padlet2->title = "Sammlung von Lieblingskatzennamen";
        $padlet2->description = "Felix, Moritz, Marty, Gloria, Pauli, Fips, Zoe. Kommentiert gerne mit euren liebsten Katzennamen :)";
        $padlet2->created_at = date("Y-m-d H:i:s");
        $padlet2->updated_at = date("Y-m-d H:i:s");
        $padlet2->save();

        $users = User::all()->pluck("id");
        $padlet2->users()->sync($users);
        $padlet2->save();

    }
}
