<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Sessions;
use Auth;
use Closure;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use PragmaRX\Google2FALaravel\Support\Authenticator;
use App\Models\ActivityLog;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    public function handle($request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $authenticator = app(Authenticator::class)->boot($request);

        if (!$authenticator->isAuthenticated()) {
            return redirect('2fa');
        }

        return $next($request);
    }

    protected function authenticated(Request $request, $user)
    {
        $request->session()->put('user', $user->username);
        $request->session()->put('client_ip', $request->ip());
        $request->session()->put('access_time', date('Y-m-d H:i:s'));

        ActivityLog::create([
            'user_id' => $user->id,
            'activity_type' => 'login',
            'action' => 'user_login',
            'url' => $request->fullUrl(),
            'method' => $request->method(),
            'ip_address' => $request->ip(),
            'input' => json_encode([]),
            'user_agent' => $request->userAgent(),
        ]);

        $user->update([
            'last_login_at' => now(),
            'last_login_ip' => $request->ip(),
        ]);




       // Check if TOTP is enabled for the user
       if ($user->totp_enabled) {// Debugging statement

        return redirect()->route('2fa.show');
        }

        if ($user->enabled == 0) {
            Auth::logout();

            // throw ValidationException::withMessages([
            //     $this->username() => [trans('auth.failed')],
            // ]);
            return back()->withErrors([
                'email' => 'This account is inactive.',
            ]);
        }



        return redirect()->intended($this->redirectPath());
    }


    protected $redirectTo = '/home';

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->redirectTo = route('dashboard.index');
    }

    public function index()
    {
        return view('auth.login');
    }

    public function show2faForm()
    {
        dd('Reached /2fa route'); // Add this line for debugging

        return view('auth.totpLogin');
    }

    public function verify2fa(Request $request)
    {
        $request->validate([
            'totp_code' => 'required',
        ]);

        $user = Auth::user();

        $authenticator = app(Authenticator::class)->boot($request);

        if (!$authenticator->verifyKey($user, $request->totp_code)) {
            return redirect()->back()->withErrors(['totp_code' => 'Invalid TOTP code']);
        }

        return redirect('/home'); // Adjust this to your desired post-2FA verification page
    }

    public function logout(Request $request)
    {
        try {
            if (Auth::check()) {
                ActivityLog::create([
                    'user_id' => Auth::id(),
                    'activity_type' => 'logout',
                    'action' => 'user_logout',
                    'url' => $request->fullUrl(),
                    'method' => $request->method(),
                    'ip_address' => $request->ip(),
                    'input' => json_encode([]),
                    'user_agent' => $request->userAgent(),
                ]);
            }

            $this->guard()->logout();
            $request->session()->flush();
            //$request->session()->regenerate();
            $request->session()->regenerateToken();

            return redirect('/login');
        } catch (Throwable $e) {
            // Log the exception details to the activity_logs table
            ActivityLog::create([
                'user_id' => Auth::id(),
                'activity_type' => 'exception',
                'action' => 'logout_exception',
                'url' => $request->fullUrl(),
                'method' => $request->method(),
                'ip_address' => $request->ip(),
                'input' => json_encode([]),
                'user_agent' => $request->userAgent(),
                'input' => json_encode([
                    'message' => $e->getMessage(),
                    'stack' => $e->getTraceAsString(),
                ]),
            ]);

            // Optionally, you can rethrow the exception or handle it differently
            throw $e;
        }
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        $remember = $request->has('remember'); // Check if the remember checkbox is checked

        if (Auth::attempt($credentials, $remember)) {
            //$this->showAlert($request);
            $notification = $this->handleConcurrentUser($request);
            if ($notification) {
                //session()->flash('alert', $notification);
                //\Log::info('Alert set:', ['message' => $notification]); // add log the alert message in laravel.log for debug

                $this->handleConcurrentUser($request);

                //return redirect()->route('login')->with('alert', $notification);
            }

            // Authentication passed, handle post-login logic
            return $this->authenticated($request, Auth::user());
        }

        // Authentication failed, redirect back with input
        return redirect()->back()->withInput($request->only('email', 'remember'))->withErrors(['email' => 'Invalid credentials']);
    }

    /*public function showAlert(Request $request) {
        $activeSession = $request->session()->getId();
        if (file_exists($activeSession)) {
            session()->flash('alert', 'This is a simple alert from controller!');
            return redirect('/alert');
        }
        //return redirect('/alert');
        return redirect('/login');
    }*/


    private function handleConcurrentUser(Request $request): ?string
    {
        $activeSessionId = $request->session()->getId();
        $user = Auth::user();

        $existingSession = $user->session_id;

        if ($existingSession && $existingSession !== $activeSessionId) {
            session()->getHandler()->destroy($existingSession);

            // update session id and save in db
            $user->session_id = $activeSessionId;
            $user->save();

            // return notification message
            //return 'Your previous session has been logged out.';

            session()->flash('alert', 'Your previous session has been logged out.');
        }

        // save the new session id if not stored
        $user->session_id = $activeSessionId; //for use this code, need to add column session_id in users table
        $user->save();

        return null;
    }
}
