<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function show()
    {
        if (Auth::check()) {
            return $this->redirectByRole(Auth::user());
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|string',
            'password'    => 'required|string',
        ]);

        $credentials = [
            'employee_code' => $request->employee_id,
            'password'      => $request->password,
        ];

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return $this->redirectByRole(Auth::user());
        }

        return back()
            ->withInput($request->only('employee_id'))
            ->withErrors(['employee_id' => 'Mã nhân viên hoặc mật khẩu không đúng.']);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    private function redirectByRole($user): \Illuminate\Http\RedirectResponse
    {
        return match ($user->role) {
            'manager' => redirect()->route('admin.dashboard'),
            default   => redirect()->route('employee.dashboard'),
        };
    }
}
