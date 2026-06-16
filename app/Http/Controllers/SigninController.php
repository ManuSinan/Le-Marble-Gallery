<?php

namespace App\Http\Controllers;

use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SigninController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function index()
    {
        if (Auth::check()) {
            return redirect()->to($this->redirectPathForUser(Auth::user()));
        }

        return view('frontend/signin/index');
    }


    protected function validateLogin(Request $request)
    {
        $request->validate([
            'login' => ['required', 'string', 'min:2'],
            'password' => ['required', 'string'],
        ], [], [
            'login' => 'Username / Mobile Number',
        ]);
    }

    public function username()
    {
        return 'login';
    }

    protected function credentials(Request $request)
    {
        $login = trim((string) $request->input('login', ''));
        $mobile = preg_replace('/\D/', '', $login);

        if ($mobile !== '' && preg_match('/^[0-9\s\-\+\(\)]+$/', $login)) {
            return [
                'mobile' => $mobile,
                'password' => $request->password,
                'status' => 'active',
            ];
        }

        return [
            'username' => $login,
            'password' => $request->password,
            'status' => 'active',
        ];
    }

    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(Request $request)
    {
        $this->validateLogin($request);

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if (method_exists($this, 'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        if ($this->attemptLogin($request)) {
            if ($request->hasSession()) {
                $request->session()->put('auth.password_confirmed_at', time());
            }

            // Handle AJAX requests
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'redirect' => $this->redirectPathForUser($request->user()),
                    'message' => __('Login successful')
                ]);
            }

            return $this->sendLoginResponse($request);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        // Handle AJAX requests with errors
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'errors' => [
                    'login' => [__('Username or mobile number is wrong.')]
                ]
            ], 422);
        }

        return $this->sendFailedLoginResponse($request);
    }

    protected function authenticated(Request $request, $user): RedirectResponse
    {
        return redirect()->to($this->redirectPathForUser($user));
    }

    protected function redirectPathForUser($user): string
    {
        if ($user && $user->role && $user->role->type === 'private') {
            return route('dashboard');
        }

        return route('home');
    }
}
