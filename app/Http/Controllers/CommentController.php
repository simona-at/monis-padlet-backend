<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Padlet;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CommentController extends Controller
{

    /**
     * function saves comment on padlet with id: $id from user with id from request
     * @param Request $request
     * @param int $padlet_id
     * @return JsonResponse
     */
    public function saveComment(Request $request, int $padlet_id) : JsonResponse {

        $request = $this->parseRequest($request);
        DB::beginTransaction();
        try {
            $padlet = Padlet::with(['users', 'images', 'likes', 'comments'])->where('id', $padlet_id)->first();

            if($padlet != null) {

                if(isset($request['comments']) && is_array($request['comments'])){
                    foreach ($request['comments'] as $comment) {
                        $comment = Comment::firstOrNew([
                            'content' => $comment['content'],
                            'user_id' => $comment['user_id'],
                            'padlet_id' => $padlet_id
                        ]);
                        $padlet->comments()->save($comment);
                    }
                }
            }
            DB::commit();
            return response()->json($padlet, 200);
        }
        catch (\Exception $e) {
            DB::rollBack();
            return response()->json("saving comment failed: " . $e->getMessage(), 420);
        }
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
