<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CmsAuditLog;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Throwable;

/** "Forgot password?" for CMS users: email a one-time link, then choose a new password. */
class AdminPasswordResetController extends Controller
{
    public function request(): View
    {
        return view('admin.auth.forgot-password');
    }

    public function email(Request $request): RedirectResponse
    {
        $data = $request->validate(['email' => ['required', 'email', 'max:255']]);
        $email = Str::lower(trim($data['email']));
        $user = User::query()->where('email', $email)->where('is_active', true)->first();

        if ($user) {
            try {
                $status = Password::broker()->sendResetLink(['email' => $email]);
            } catch (Throwable $exception) {
                report($exception);
                // Honest failure: the link was not sent, so do not claim it was.
                return back()->withInput()->withErrors(['email' => 'The website could not send email just now. Please try again later, or ask another CMS user to set a new password for you under Users.']);
            }
            if ($status === Password::RESET_THROTTLED) {
                return back()->withInput()->withErrors(['email' => 'A reset link was sent recently. Please wait a minute before asking again.']);
            }
        }

        // Same message whether or not the address has an account, so the form cannot be used to discover accounts.
        return back()->with('status', 'If that address belongs to an active CMS account, a reset link is on its way. Check your inbox and spam folder.');
    }

    public function edit(Request $request, string $token): View
    {
        return view('admin.auth.reset-password', ['token' => $token, 'email' => (string) $request->query('email', '')]);
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'token' => ['required', 'string'],
            'email' => ['required', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:12', 'confirmed'],
        ]);
        $data['email'] = Str::lower(trim($data['email']));

        $status = Password::broker()->reset($data, function (User $user, string $password): void {
            $user->forceFill(['password' => Hash::make($password), 'remember_token' => Str::random(60)])->save();
            DB::table('sessions')->where('user_id', $user->id)->delete();
            CmsAuditLog::query()->create(['user_id' => $user->id, 'action' => 'user.password-reset', 'entity_type' => 'user', 'entity_key' => (string) $user->id, 'metadata' => ['email' => $user->email], 'created_at' => now()]);
        });

        if ($status !== Password::PASSWORD_RESET) {
            return back()->withInput($request->only('email'))->withErrors(['email' => 'This reset link is not valid any more. Ask for a new one below.']);
        }

        return to_route('admin.login')->with('status', 'Your password has been changed. Sign in with your new password.');
    }
}
