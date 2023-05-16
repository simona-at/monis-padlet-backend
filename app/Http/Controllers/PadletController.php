<?php

namespace App\Http\Controllers;

use App\Models\Image;
use App\Models\Padlet;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PadletController extends Controller
{
    public function index() : JsonResponse {
//        $padlets = Padlet::all();
        $padlets = Padlet::with(['users', 'images', 'likes', 'comments'])->get();
        return response()->json($padlets, 200);
    }

    public function findByID(int $id) : JsonResponse {
        $padlet = Padlet::where('id', $id)->with(['users', 'images', 'likes', 'comments'])->first();
        return $padlet != null ? response()->json($padlet, 200) : response()->json(null, 200);
    }

    public function checkID(string $id) : JsonResponse {
        $padlet = Padlet::where('id', $id)->first();
        return $padlet != null ? response()->json(true, 200) : response()->json(false, 200);
    }


    /**
     * save a new padlet
     * @param Request $request
     * @return JsonResponse
     */
    //TODO: user id evtl als parameter mitgeben und nicht im request selbst?
    public function save(Request $request) : JsonResponse {

        $request = $this->parseRequest($request);
        DB::beginTransaction();

        try {
//            $padlet = Padlet::create($request->all());

            $is_private = $request['is_private'];
            if(!isset($request['users'])) $is_private = 0;

            $padlet = Padlet::create([
                'title' => $request['title'],
                'description' => $request['description'],
                'is_private' => $is_private
            ]);

            if(isset($request['users']) && is_array($request['users'])){
                $user = $request['users'][0]; //nur den ersten User im Request berücksichtigen –> mehr sollte eigentlich nicht möglich sein
                $user = User::firstOrNew([
                    'id' => $user['id']
                ]);
                $padlet->users()->save($user);
            }

            if(isset($request['images']) && is_array($request['images'])){
                foreach ($request['images'] as $image) {
                    $image = Image::firstOrNew([
                        'url' => $image['url'],
                        'title' => $image['title']
                    ]);
                    $padlet->images()->save($image);
                }
            }

            DB::commit();
            return response()->json($padlet, 200);
        }
        catch (\Exception $e) {
            DB::rollBack();
            return response()->json("saving padlet failed: " . $e->getMessage(), 420);
        }
    }




    private function parseRequest(Request $request) : Request {
        $date = new \DateTime($request->published);
        $request['published'] = $date;
        return $request;
    }
}
