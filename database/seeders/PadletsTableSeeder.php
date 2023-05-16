<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Image;
use App\Models\Like;
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
        $padlet1->save();

        $image1 = new Image();
        $image1->url = "https://images.unsplash.com/photo-1517849845537-4d257902454a?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1335&q=80";
        $image1->title = "Der Hund meiner Nachbarin";

        $padlet1->images()->saveMany([$image1]);

        $like1 = new Like();
//        $like1->value = "1";
        $like1->user_id = "1";
        $padlet1->likes()->save($like1);

        $like2 = new Like();
//        $like2->value = "1";
        $like2->user_id = "2";
        $padlet1->likes()->save($like2);

        $like3 = new Like();
//        $like3->value = "1";
        $like3->user_id = "3";
        $padlet1->likes()->save($like3);




        $comment1 = new Comment();
        $comment1->content = "Das ist das erste Kommentar unter Padlet Nr. 1";
        $comment1->user_id = "1";
        $padlet1->comments()->save($comment1);


//        $user = User::all()->where('email', "hallo@simona.at");
//        $padlet1->users()->sync($user);
//        $padlet1->save();

        $padlet2 = new Padlet();
        $padlet2->title = "Sammlung von Lieblingskatzennamen";
        $padlet2->description = "Felix, Moritz, Marty, Gloria, Pauli, Fips, Zoe. Kommentiert gerne mit euren liebsten Katzennamen :)";
        $padlet2->is_private = true;
        $padlet2->save();


        $like4 = new Like();
//        $like4->value = "1";
        $like4->user_id = "2";
        $padlet2->likes()->save($like4);

        $users = User::all();//->pluck("id");
        $padlet2->users()->sync($users);
        $padlet2->save();

        $viewer = $padlet2->users->where('email', "hallo@mjk-media.com")->first(); //funktioniert nur an einzelnen user -> keine arrays
        $viewer->pivot->user_role = "viewer";
        $viewer->pivot->save();

        $viewer = $padlet2->users->where('first_name', "Alina")->first(); //funktioniert nur an einzelnen user -> keine arrays
        $viewer->pivot->user_role = "editor";
        $viewer->pivot->save();


//        $user1 = User::all()->where('email', "=", "hallo@mjk-media.com")->first();
//        $user1->first_name = "Susi";
//        $user1->save();


//        $padlet = Padlet::find(1);
//        $padlet->title = "neu umbenannt ;)";
//        foreach ($padlet->users as $user){
//            $user->pivot->user_role = "viewer";
//            $user->pivot->save();
//        }
//        $padlet->save();



        $padlet1_users = User::all()->where('email', "hallo@simona.at");
        $padlet1->users()->sync($padlet1_users);
        $padlet1->save();

        //Use-Case: Nutzer (hallo@mjk-media.com) mit Rolle (viewer) zu 1. Padlet hinzufügen
        $newuser= User::all()->where('email', "hallo@mjk-media.com")->first();
        $padlet1_users->push($newuser);
        $padlet1->users()->sync($padlet1_users);
        $newuser = $padlet1->users->where('email', "hallo@mjk-media.com")->first();
        $newuser->pivot->user_role = "viewer";
        $newuser->pivot->save();
        $padlet1->save();


        $padlet3 = new Padlet();
        $padlet3->title = "Padlet von anonymem Nutzer";
        $padlet3->description = "anonymer Inhalt";
        $padlet3->save();

    }
}
