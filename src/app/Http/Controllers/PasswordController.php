<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Traits\CategoryTrait;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Support\Facades\Input;


class PasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords , CategoryTrait;

    /**
     * redirect path.
     *
     * @return string
     */
    private $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */


    public function getEmail(){


        // Gets the query string from our form submission
        $query = Input::get('search');

        //Get all category(need to header menu)
        $categoryAll = $this->categoryAll();

        $token = $_COOKIE["XSRF-TOKEN"];

        // Returns an array of products that have the query string located somewhere within
        // our products product name. Paginates them so we can break up lots of search results.
        $search = \DB::table('products')->where('product_name', 'LIKE', '%' . $query . '%')->paginate(10);

        return view('auth.reset', compact('query', 'search','categoryAll','token'));
    }

}
