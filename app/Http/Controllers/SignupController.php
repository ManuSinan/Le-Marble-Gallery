<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class SignupController extends Controller
{
    public function index(Request $request)
    {
        if(Auth::check()){
            $user = Auth::user();

            if ($user && $user->role && $user->role->type === 'private') {
                return redirect()->route('dashboard');
            }

            return redirect()->route('home');
        }

        if ($request->boolean('restart')) {
            $request->session()->forget([
                'signup_pending_user_id',
                'signup_pending_mobile',
            ]);
        }

        $pendingUser = $this->pendingSignupUser($request);
        return view('frontend/signup/index', compact('pendingUser'));
    }

    public function store(Request $request)
    {
        $request->merge([
            'mobile' => preg_replace('/\D/', '', (string) $request->input('mobile', '')),
        ]);

        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'min:1', 'max:100'],
            'mobile' => [
                'required',
                'regex:/^[0-9]{10,15}$/',
                Rule::unique('users', 'mobile')->where(function ($query) {
                    $query->where('mobile_verified', 1);
                }),
                Rule::unique('users', 'username')->where(function ($query) {
                    $query->where('mobile_verified', 1);
                }),
            ],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ], [
            'mobile.unique' => __('Mobile number is already registered.'),
        ]);

        $validator->setAttributeNames([
            'mobile' => __('Mobile Number'),
        ]);

        if (!$validator->passes()) {
            if (!$request->expectsJson() && !$request->ajax()) {
                return back()->withErrors($validator)->withInput();
            }

            return response()->json([
                'errors' => $validator->errors(),
            ]);
        }

        DB::beginTransaction();
        try {
            $otp = (string) mt_rand(100000, 999999);

            $baseUsername = Str::slug($request->name);
            if (empty($baseUsername)) {
                $baseUsername = 'user';
            }
            $username = $baseUsername;
            $counter = 1;
            $existingPending = User::query()
                ->where('mobile', $request->mobile)
                ->where('status', 'created')
                ->where('mobile_verified', 0)
                ->first();
            $currentUserId = $existingPending ? $existingPending->id : 0;
            while (User::where('username', $username)->where('id', '!=', $currentUserId)->exists()) {
                $username = $baseUsername . $counter;
                $counter++;
            }

            $payload = [
                'name' => $request->name,
                'mobile' => $request->mobile,
                'username' => $username,
                'password' => Hash::make($request->password),
                'role_id' => 1,
                'status' => 'created',
                'mobile_verified' => 0,
                'email_verified' => 0,
                'email' => null,
                'fcm' => null,
                'otp' => $otp,
            ];

            $user = User::query()
                ->where('mobile', $request->mobile)
                ->where('status', 'created')
                ->where('mobile_verified', 0)
                ->first();

            if ($user) {
                $user->update($payload);
            } else {
                $user = User::create($payload);
            }

            if (!$this->sendRegistrationOtp($user)) {
                DB::rollBack();
                return $this->signupErrorResponse(
                    $request,
                    'mobile',
                    __('WhatsApp OTP could not be sent. Please check WhatsApp settings and try again.')
                );
            }
        } catch (\Throwable $e) {
            DB::rollBack();
            report($e);
            return $this->signupErrorResponse(
                $request,
                'mobile',
                __('Something went wrong.')
            );
        }

        DB::commit();
        $this->rememberPendingSignup($request, $user);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'user_id' => $user->id,
                'mobile' => $user->mobile,
                'message' => __('WhatsApp OTP sent successfully.'),
            ]);
        }

        return redirect()
            ->route('signup')
            ->with('signup_status', __('WhatsApp OTP sent successfully. Enter the code to verify your number.'));
    }
    public function requestOtp(Request $request)
    {
        $userId = $request->input('id') ?: $request->session()->get('signup_pending_user_id');
        $user = User::query()
            ->where('id', $userId)
            ->where('status', 'created')
            ->where('mobile_verified', 0)
            ->first();

        if (!$user) {
            $request->session()->forget([
                'signup_pending_user_id',
                'signup_pending_mobile',
            ]);

            return $this->signupErrorResponse(
                $request,
                'otp',
                __('Signup session expired. Please register again.')
            );
        }

        DB::beginTransaction();
        try {
            $user->update([
                'otp' => (string) mt_rand(100000, 999999),
            ]);

            if (!$this->sendRegistrationOtp($user)) {
                DB::rollBack();
                return $this->signupErrorResponse(
                    $request,
                    'otp',
                    __('WhatsApp OTP could not be sent. Please check WhatsApp settings and try again.')
                );
            }
        } catch (\Throwable $e) {
            DB::rollBack();
            report($e);
            return $this->signupErrorResponse(
                $request,
                'otp',
                __('Something went wrong.')
            );
        }
        DB::commit();

        $this->rememberPendingSignup($request, $user);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => __('WhatsApp OTP sent successfully.'),
                'mobile' => $user->mobile,
            ]);
        }

        return redirect()
            ->route('signup')
            ->with('signup_status', __('A fresh WhatsApp OTP was sent to your number.'));
    }


    public function signup(Request $request)
    {
        return $this->store($request);
    }

    public function verify(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => ['required', 'integer'],
            'otp' => ['required', 'digits:6'],
        ], [
            'otp.digits' => __('Invalid OTP, Try again!'),
        ]);

        if (!$validator->passes()) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'errors' => $validator->errors(),
                ], 422);
            }

            return redirect()
                ->route('signup')
                ->withErrors($validator);
        }

        $pendingUser = $this->pendingSignupUser($request);
        if (!$pendingUser || (int) $pendingUser->id !== (int) $request->input('id')) {
            return $this->signupErrorResponse(
                $request,
                'otp',
                __('Signup session expired. Please register again.')
            );
        }

        DB::beginTransaction();
        try {
            $user = User::query()
                ->where('id', $request->input('id'))
                ->where('status', 'created')
                ->where('mobile_verified', 0)
                ->first();

            if (!$user || (string) $user->otp !== (string) $request->input('otp')) {
                DB::rollBack();
                return $this->signupErrorResponse(
                    $request,
                    'otp',
                    __('Invalid OTP, Try again!')
                );
            }

            if ($user->updated_at->lt(Carbon::now()->subMinutes(3))) {
                DB::rollBack();
                return $this->signupErrorResponse(
                    $request,
                    'otp',
                    __('OTP expired, Try again!')
                );
            }

            $duplicateVerifiedUser = User::query()
                ->where('id', '!=', $user->id)
                ->where('mobile', $user->mobile)
                ->where('mobile_verified', 1)
                ->exists();

            if ($duplicateVerifiedUser) {
                DB::rollBack();
                $request->session()->forget([
                    'signup_pending_user_id',
                    'signup_pending_mobile',
                ]);
                return $this->signupErrorResponse(
                    $request,
                    'mobile',
                    __('Mobile number is already registered.')
                );
            }

            $user->update([
                'status' => 'active',
                'mobile_verified' => 1,
                'otp' => null,
                'verification_code' => unicode(),
                'username' => $user->username ?: Str::slug($user->name . '-' . $user->id),
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            report($e);
            return $this->signupErrorResponse(
                $request,
                'otp',
                __('Something went wrong.')
            );
        }
        DB::commit();

        $request->session()->forget([
            'signup_pending_user_id',
            'signup_pending_mobile',
        ]);

        Auth::login($user);
        if ($request->hasSession()) {
            $request->session()->regenerate();
        }

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'redirect' => route('home'),
            ]);
        }

        return redirect()->route('home');
    }

    protected function pendingSignupUser(Request $request): ?User
    {
        $userId = $request->session()->get('signup_pending_user_id');

        if (!$userId) {
            return null;
        }

        $user = User::query()
            ->where('id', $userId)
            ->where('status', 'created')
            ->where('mobile_verified', 0)
            ->first();

        if (!$user) {
            $request->session()->forget([
                'signup_pending_user_id',
                'signup_pending_mobile',
            ]);
        }

        return $user;
    }

    protected function rememberPendingSignup(Request $request, User $user): void
    {
        $request->session()->put('signup_pending_user_id', $user->id);
        $request->session()->put('signup_pending_mobile', $user->mobile);
    }

    protected function sendRegistrationOtp(User $user): bool
    {
        if (app()->environment('testing')) {
            return true;
        }

        $hasWhatsAppConfig = filled(config('whatsapp.phone_number_id')) && filled(config('whatsapp.api_key'));
        if (config('whatsapp.use_whatsapp_otp') || $hasWhatsAppConfig) {
            return (bool) sendWhatsAppOtp($user->mobile, $user->otp);
        }

        $smsResult = sendSms(
            $user->mobile,
            'Your OTP to register is ' . $user->otp . '. It will be valid for 3 minutes.',
            '1307164086364084790'
        );

        return $smsResult !== false && strpos((string) $smsResult, 'Error') === false;
    }

    protected function signupErrorResponse(Request $request, string $field, string $message)
    {
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'errors' => [
                    $field => [$message],
                ],
            ], 422);
        }

        return redirect()
            ->route('signup')
            ->withErrors([$field => $message]);
    }
}
