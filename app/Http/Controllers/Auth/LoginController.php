<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Auth;
use Closure;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use PragmaRX\Google2FALaravel\Support\Authenticator;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    public function handle($request, Closure $next)
    {
        $authenticator = app(Authenticator::class)->boot($request);

        if (!$authenticator->isAuthenticated()) {
            return redirect('2fa');
        }

        return $next($request);
    }

    protected function authenticated(Request $request, $user)
    {
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

            throw ValidationException::withMessages([
                $this->username() => [trans('auth.failed')],
            ]);
        }
    }
    

    protected $redirectTo = '/main';

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

        return redirect('/main'); // Adjust this to your desired post-2FA verification page
    }

    public function logout(Request $request)
    {
        $this->guard()->logout();
        $request->session()->flush();
        $request->session()->regenerate();

        return redirect('/login');
    }
}
