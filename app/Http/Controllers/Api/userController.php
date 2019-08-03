<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\userResource;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class userController extends Controller
{
    public function index(){}

    public function show(Request $request){
        return new userResource($request->user());
    }
}
