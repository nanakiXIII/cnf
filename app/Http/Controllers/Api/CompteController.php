<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repository\AccountRepository;
use App\Serie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;


class CompteController extends Controller {

    /**
     * @var AccountRepository
     */
    private $accountRepository;

    public function __construct(AccountRepository $accountRepository)
    {
        $this->accountRepository = $accountRepository;
    }

    public function index(Request $request){
        return $this->accountRepository->getAbonnement($request->user());

    }

    public function serieAboLog(Request $request, string $type){
        return $this->accountRepository->getAbonnements($request->user(), $type);

    }
    public function serieAbo(Request $request, string $type){
       return Serie::where('publication', true)->where('type', $type)->get();
    }

}