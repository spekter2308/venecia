<?php

namespace App\Http\Controllers;

use App\Callback;
use App\Cart;
use Auth;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $user;

    /**
     * Make a constructor to initialize Auth check.
     */
    public function __construct() {
        // set user = to the currently authenticated user.
        $this->user = Auth::user();
        $callback = Callback::where('status',0)->get()->count();
        $session_id = \Session::getId();
        $cart_count = Cart::where('session_id', '=', $session_id)->count();

        view()->share('signedIn', Auth::check());
        view()->share('user', $this->user);
        view()->share('countCallback', $callback);
        view()->share('cart_count', $cart_count);
    }
}
