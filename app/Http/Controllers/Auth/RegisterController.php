<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Http\Controllers\Controller;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
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
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:6|confirmed',
            'password_confirmation' => 'required|string|min:6',
        ], [
            'name.required' => '請填寫姓名',
            'email.required' => '請填寫信箱',
            'email.email' => '密碼格式有誤',
            'password.required' => '請填寫密碼',
            'password.min' => '密碼需大於六碼',
            'password.confirmed' => '密碼與確認密碼不一致',
            'password_confirmation.required' => '請填寫確認密碼',
            'password_confirmation.min' => '確認密碼需大於六碼',
        ]);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function facebookValidator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'fbid' => 'required',
        ], [
            'name.required' => '請填寫姓名',
            'email.required' => '請填寫信箱',
            'email.email' => '密碼格式有誤',
            'fbid.required' => '請填寫密碼',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
    }

    public function index()
    {
        $data = [];
        return view('register', compact('data'));
    }

    public function registerAction(Request $request) {
        $result = $this->validator($request->all());
        if ($result->fails()) {
            $message = '';
            foreach ($result->getMessageBag()->toArray() as $value) {
                $message .= implode('<br />', $value).'<br />';
            }
            return redirect()->route('register')->with(['result' => 0, 'message' => $message]);
        }
        $data = [];
        $data['name'] = $request->post('name');
        $data['email'] = $request->post('email');
        $data['password'] = Hash::make($request->post('password'));
        $result = resolve(UserService::class)->checkExistEmail($data['email']);
        if ($result['result']) {
            $result = resolve(UserService::class)->addData($data);
            if ($result['result']) {
                return redirect()->route('login')->with(['result' => 1, 'message' => $result['error']['message']]);
            } else {
                return redirect()->route('register')->with(['result' => 0, 'message' => $result['error']['message']]);
            }
        } else {
            return redirect()->route('register')->with(['result' => 0, 'message' => $result['error']['message']]);
        }
    }
    public function facebookRegisterAction(Request $request) {
        $result = $this->facebookValidator($request->all());
        if ($result->fails()) {
            $message = '';
            foreach ($result->getMessageBag()->toArray() as $value) {
                $message .= implode('<br />', $value).'<br />';
            }
            return response()->json(['result' => 0, 'message' => $message]);
        }
        $data = [];
        $data['name'] = $request->post('name');
        $data['email'] = $request->post('email');
        $data['fbid'] = $request->post('fbid');

        $result = resolve(UserService::class)->checkExistEmail($data['email']);
        if ($result['result']) {
            $result = resolve(UserService::class)->addData($data);
            if ($result['result']) {
                return response()->json(['result' => 1, 'message' => $result['error']['message']]);
            } else {
                return response()->json(['result' => 0, 'message' => $result['error']['message']]);
            }
        } else {
            return response()->json(['result' => 0, 'message' => $result['error']['message']]);
        }
    }
}
