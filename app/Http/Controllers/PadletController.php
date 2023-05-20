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
        $padlets = Padlet::with(['users', 'images', 'likes', 'comments'])->get();
        return response()->json($padlets, 200);
    }

    /**
     * function returns padlet with id: $id
     * @param int $id
     * @return JsonResponse
     */
    public function findByID(int $id) : JsonResponse {
        $padlet = Padlet::where('id', $id)->with(['users', 'images', 'likes', 'comments'])->first();
        return $padlet != null ? response()->json($padlet, 200) : response()->json(null, 200);
    }

    /**
     * function checks if padlet with id: $id exists
     * @param string $id
     * @return JsonResponse
     */
    public function checkID(string $id) : JsonResponse {
        $padlet = Padlet::where('id', $id)->first();
        return $padlet != null ? response()->json(true, 200) : response()->json(false, 200);
    }


    /**
     * function saves a new padlet with title, description, visibility and images
     * @param Request $request
     * @return JsonResponse
     */
    public function save(Request $request) : JsonResponse {

        $request = $this->parseRequest($request);
        DB::beginTransaction();

        try {
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


    /**
     * function updates existing padlet with id: $id; overrides images and users (and enables to set user roles)
     * @param Request $request
     * @return JsonResponse
     */
    public function update(Request $request, int $padlet_id) : JsonResponse {

        DB::beginTransaction();
        try {
            $padlet = Padlet::with(['users', 'images', 'likes', 'comments'])->where('id', $padlet_id)->first();
            $user_ids = [];
            if($padlet != null) {

                if($padlet['users']){
                    foreach ($padlet['users'] as $user){
                        if($user['pivot']['user_role'] == "owner"){
                            array_push($user_ids, $user['id']);
                        }
                    }
                }

                $request = $this->parseRequest($request);
                $padlet->update($request->all());
                $padlet->images()->delete();

                if (isset($request['images']) && is_array($request['images'])) {
                    foreach ($request['images'] as $image) {
                        $image = Image::firstOrNew([
                            'url' => $image['url'],
                            'title' => $image['title'],
                            'padlet_id' => $padlet_id
                        ]);
                        $padlet->images()->save($image);
                    }
                }

                //add ids to user_ids array with only knowing email
                if (isset($request['users']) && is_array($request['users'])) {
                    foreach ($request['users'] as $user){
                        $user_mail = $user['email'];
                        $newuser = User::with(['padlets'])->where('email', $user_mail)->first();
                        array_push($user_ids, $newuser['id']);
                    }
                }

                //remove possible multiple user ids (if user is for example added twice):
                $user_ids = array_unique($user_ids);
                $user_ids = array_values($user_ids);

                $padlet->users()->sync($user_ids);

                //set right roles to users
                if (isset($request['users']) && is_array($request['users'])) {
                    foreach ($request['users'] as $user){
                        $user_mail = $user['email'];
                        $newuser = User::with(['padlets'])->where('email', $user_mail)->first();
                        $user_role = $user['user_role'];
                        $currentPadlet = $newuser['padlets']->where('id', $padlet_id)->first();
                        $currentPadlet['pivot']->update(['user_role' => $user_role]);
                    }
                }
                $padlet->save();
            }
            DB::commit();
            $updated_padlet = Padlet::with(['users', 'images', 'likes', 'comments'])->where('id', $padlet_id)->first();
            return response()->json($updated_padlet, 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json("saving padlet failed: " . $e->getMessage(), 420);
        }

    }


    /**
     * function deletes padlet with id: $id
     * @param string $id
     * @return JsonResponse
     */
    public function delete(string $id) : JsonResponse{
        $padlet = Padlet::where('id', $id)->first();
        if($padlet != null){
            $padlet->delete();
            return response()->json('padlet ('. $id .') successfully deleted', 200);
        }
        else
            return response()->json('padlet could not be deleted - it does not exist', 422);
    }


    /**
     * @param Request $request
     * @return Request
     * @throws \Exception
     */
    private function parseRequest(Request $request) : Request {
        $date = new \DateTime($request->published);
        $request['published'] = $date;
        return $request;
    }
}
