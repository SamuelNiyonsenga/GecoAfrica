<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\CmsPasswordResetMail;
use App\Models\CmsAuditLog;
use App\Models\User;
use App\Support\EnquiryMailer;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Throwable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AdminUserController extends Controller
{
    public function index(): View
    {
        return view('admin.users.index', ['users' => User::query()->orderBy('name')->get()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $invite = $request->boolean('send_invite');
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => [$invite ? 'nullable' : 'required', 'string', 'min:12'],
        ]);
        $data['email'] = mb_strtolower(trim($data['email']));
        // With an invitation the person chooses their own password; until then nobody knows this one.
        $data['password'] = Hash::make($data['password'] ?? Str::random(40));
        $data['is_active'] = true;
        $user = User::query()->create($data);
        $this->audit($request, 'user.create', $user);

        if (!$invite) {
            return back()->with('success', 'User account created. Share the temporary password with them securely.');
        }
        $error = $this->sendLink($user, true);

        return $error === null
            ? back()->with('success', 'User account created. An email with a link to choose their password was sent to '.$user->email.'.')
            : back()->withErrors(['user' => 'The account was created, but the invitation email could not be sent ('.$error.'). Set a temporary password for them below instead, or check Email delivery.']);
    }

    /** Email someone a link to choose a new password, instead of typing one for them. */
    public function sendResetLink(Request $request, User $user): RedirectResponse
    {
        abort_unless($user->is_active, 404);
        $error = $this->sendLink($user, false);
        $this->audit($request, 'user.reset-link', $user);

        return $error === null
            ? back()->with('success', 'A password reset link was emailed to '.$user->email.'.')
            : back()->withErrors(['user' => 'The reset email could not be sent ('.$error.'). Check Email delivery, or set a password for them here instead.']);
    }

    /** Returns null when sent, or a short reason when the email failed. */
    private function sendLink(User $user, bool $invitation): ?string
    {
        try {
            $token = Password::broker()->createToken($user);
            Mail::to($user->email, $user->name)->send(new CmsPasswordResetMail($user, $token, $invitation));
            return null;
        } catch (Throwable $exception) {
            report($exception);
            return EnquiryMailer::describe($exception);
        }
    }

    public function resetPassword(Request $request, User $user): RedirectResponse
    {
        $data = $request->validate(['password' => ['required', 'string', 'min:12']]);
        $user->forceFill(['password' => Hash::make($data['password'])])->save();
        DB::table('sessions')->where('user_id', $user->id)->delete();
        $this->audit($request, 'user.password', $user);

        return back()->with('success', 'Password updated. The user will need to sign in again.');
    }

    public function setActive(Request $request, User $user): RedirectResponse
    {
        $active = $request->boolean('active');
        $actor = $request->user();
        if (!$active && $actor?->is($user)) {
            return back()->withErrors(['user' => 'You cannot deactivate your own account.']);
        }
        if (!$active && User::query()->where('is_active', true)->where('id', '<>', $user->id)->count() === 0) {
            return back()->withErrors(['user' => 'The last active account cannot be deactivated.']);
        }

        $user->forceFill(['is_active' => $active])->save();
        if (!$active) {
            DB::table('sessions')->where('user_id', $user->id)->delete();
        }
        $this->audit($request, $active ? 'user.activate' : 'user.deactivate', $user);

        return back()->with('success', $active ? 'User account reactivated.' : 'User account deactivated.');
    }

    private function audit(Request $request, string $action, User $user): void
    {
        CmsAuditLog::query()->create([
            'user_id' => $request->user()?->id,
            'action' => $action,
            'entity_type' => 'user',
            'entity_key' => (string) $user->id,
            'metadata' => ['email' => $user->email],
            'created_at' => now(),
        ]);
    }
}
