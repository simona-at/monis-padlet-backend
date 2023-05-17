<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\Padlet;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LikeController extends Controller
{
    /**
     * function saves like on padlet with id: $id from user with id from request
     * @param Request $request
     * @param int $padlet_id
     * @return JsonResponse
     */
    public function saveLike(Request $request, int $padlet_id) : JsonResponse {

        $request = $this->parseRequest($request);
        DB::beginTransaction();
        try {
            $padlet = Padlet::with(['users', 'images', 'likes', 'comments'])->where('id', $padlet_id)->first();

            if($padlet != null) {

                if(isset($request['likes']) && is_array($request['likes'])){
                    foreach ($request['likes'] as $like) {
                        $like = Like::firstOrNew([
                            'user_id' => $like['user_id'],
                            'padlet_id' => $padlet_id
                        ]);
                        $padlet->likes()->save($like);
                    }
                }
            }
            DB::commit();
            return response()->json($padlet, 200);
        }
        catch (\Exception $e) {
            DB::rollBack();
            return response()->json("saving like failed: " . $e->getMessage(), 420);
        }
    }


    /**
     * function deletes like from padlet with id: $id from user with id from request
     * @param Request $request
     * @param string $id
     * @return JsonResponse
     */
    public function deleteLike(Request $request, string $id) : JsonResponse{

        $request = $this->parseRequest($request);
        $padlet = Padlet::where('id', $id)->first();
        if($padlet != null){

            if(isset($request['likes']) && is_array($request['likes'])){
                foreach ($request['likes'] as $like) {
                    $deleteLike = Like::where('padlet_id', $id)->where('user_id', $like['user_id']);
                    $deleteLike->delete();
                }
            }

            return response()->json('likes on padlet ('. $id .') successfully deleted', 200);
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
