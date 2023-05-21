<?php

namespace App\Http\Controllers;

use App\Models\Padlet;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function getAllUsers() : JsonResponse {
        $users = User::with(['padlets', 'likes', 'comments'])->get();
        return response()->json($users, 200);
    }
}
