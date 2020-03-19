<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\UserService;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
//        $this->middleware('guest')->except('logout');
    }

    public function login()
    {
        $data = [];
        return view('login', compact('data'));
    }

    public function loginAction(Request $request)
    {
        $email = $request->input('email');
        $password = $request->input('password');

        if (Auth::guard()->attempt(['email' => $email, 'password' => $password])) {
//            $user = Auth::guard()->user();
            return redirect()->route('home');
        } else {
            return redirect()->route('login')->with(['result' => 0, 'message' => '登入失敗']);
        }
    }

    public function facebookLoginAction(Request $request)
    {
        $fbid = $request->input('fbid');
        $result = resolve(UserService::class)->getDataByFbid($fbid);

        if ($result['result']) {
            Auth::login($result['data']);
            return response()->json(['result' => 1, 'message' => $result['error']['message']]);
        } else {
            return response()->json(['result' => 0, 'message' => '登入失敗']);
        }
    }

    public function logout(Request $request)
    {
        Auth::guard()->logout();
        return redirect()->route('login');
    }
}
