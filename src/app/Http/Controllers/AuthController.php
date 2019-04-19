<?php

namespace App\Http\Controllers;

use App\Cart;
use App\Http\Traits\CategoryTrait;
use App\User;
use App\Category;
use App\Mailers\AppMailers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use App\Http\Requests\RegistrationRequest;
use Mail;

class AuthController extends Controller
{
    use CategoryTrait;
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */



    /**
     * Get the Registration View.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getRegister() {
        // Gets the query string from our form submission
        $query = Input::get('search');
        //Get all category(need to header menu)
        $categoryAll = $this->categoryAll();
        // Returns an array of products that have the query string located somewhere within
        // our products product name. Paginates them so we can break up lots of search results.
        $search = \DB::table('products')->where('product_name', 'LIKE', '%' . $query . '%')->paginate(10);

        return view('auth.register', compact('query', 'search','categoryAll'));
    }


    /**
     * Validate and create the user in the Database.
     *
     * @param RegistrationRequest $request
     * @param AppMailers $mailer
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postRegister(RegistrationRequest $request, AppMailers $mailer) {

        // Create the user in the DB.
        $user = User::create([
            'email' => $request->input('email'),
            'username' => $request->input('username'),
            'password' => bcrypt($request->input('password')),
            'verified' => 0,
        ]);

        /**
         * send email conformation to user that just registered.
         * -- sendEmailConfirmationTo is in Mailers/AppMailers.php --
         */
        //$mailer->sendEmailConfirmationTo($user);

        Mail::send('auth.confirm',  ['user'=>$user], function ($message) use ($user) {
            $message->from('admin@venezia-online.com.ua' ,'Адміністратор');
            $message->to($user->email)->subject('Підтвердження реєстрації');
        });

        // Flash a info message saying you need to confirm your email.
        flash()->overlay('Інформація', 'Будь ласка, підтвердіть свою адресу електронної пошти у вашій поштовій скриньці.');

        return redirect()->back();

    }


    /**
     * Get the user token, an make check id email is confirmed.
     * -- confirmEmail located in User.php Model.
     *
     * @param $token
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function confirmEmail($token) {
        // Get the user with token, or fail.
        $user = User::whereToken($token)->firstOrFail();

        if ($user) {
            $user->confirmEmail();
            Auth::login($user);
            $session_id = \Session::getId();
            $new_session_id = \Session::getId();
            Cart::where('session_id', $session_id)
                ->update(['session_id' => $new_session_id]);
        }

        // Flash a info message saying you need to confirm your email.
        flash()->success('Успіх', 'Ви підтвердили вашу пошту. Вдалих покупок.');

        return redirect('/');
    }


    /** ----------------------------------------------------------------------------------- */



    /**
     * Get the Login View.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getLogin() {
        // Gets the query string from our form submission
        $query = Input::get('search');

        //Get all category(need to header menu)
        $categoryAll = $this->categoryAll();

        // Returns an array of products that have the query string located somewhere within
        // our products product name. Paginates them so we can break up lots of search results.
        $search = \DB::table('products')->where('product_name', 'LIKE', '%' . $query . '%')->paginate(10);

        return view('auth.login', compact('query', 'search','categoryAll'));
    }

    
    /**
     * Login the user.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function postLogin(Request $request) {

        // Validate email and password.
        $this->validate($request, [
            'email'    => 'required|email|exists:users,email',
            'password' => 'required|'
        ]);

        $session_id = \Session::getId();

        // login in user if successful
        if ($this->signIn($request)) {
            //flash()->overlay('Вдалих покупок', 'Успішна авторизація!',"success");
            $new_session_id = \Session::getId();
            Cart::where('session_id', $session_id)
                ->update(['session_id' => $new_session_id]);
            return redirect('/');
        }

        // Else, show error message, and redirect them back to login.php.
        // flash()->customErrorOverlay('Помилка', 'Неправельно введена інформація.');

        return redirect('login')->withErrors(['password'=>'Неправильний пароль']);
    }


    /**
     * Attempt to sign in the user.
     *
     * @param  Request $request
     * @return boolean
     */
    protected function signIn(Request $request) {
        return Auth::attempt($this->getCredentials($request), $request->has('remember'));
    }


    /**
     * Get the user credentials to login.
     *
     * @param Request $request
     * @return array
     */
    protected function getCredentials(Request $request) {
        return [
            'email'    => $request->input('email'),
            'password' => $request->input('password'),
            'verified' => true
        ];
    }


    /**
     * Logout user.
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function logout() {
        Auth::logout();
        return redirect('/');
    }


}
