<?php
namespace App\Http\Controllers\Api;

use App\Downloads;
use App\Episodes;
use App\Events\userEvent;
use App\Http\Controllers\Controller;
use App\Http\Resources\staffResource;
use App\Repository\AccountRepository;
use App\Serie;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;


class staffController extends Controller {

    public function index(){
        $staff = User::where('equipe', 1)->orderBy('name', 'ASC')->get();
        return staffResource::collection($staff);
    }

}